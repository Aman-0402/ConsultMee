from django.contrib.auth import get_user_model
from rest_framework import serializers
from rest_framework_simplejwt.serializers import TokenObtainPairSerializer
from consultants.models import ConsultantProfile

User = get_user_model()


class UserSerializer(serializers.ModelSerializer):
    class Meta:
        model = User
        fields = (
            'id',
            'username',
            'email',
            'first_name',
            'last_name',
            'full_name',
            'phone',
            'state',
            'identity',
            'interest1',
            'interest2',
            'interest3',
            'profile_img',
            'role',
        )
        read_only_fields = ('id',)


class RegisterSerializer(serializers.ModelSerializer):
    password = serializers.CharField(write_only=True, min_length=8)
    area_of_expertise = serializers.CharField(write_only=True, required=False, allow_blank=True)
    bio = serializers.CharField(write_only=True, required=False, allow_blank=True)
    experience = serializers.CharField(write_only=True, required=False, allow_blank=True)
    hourly_rate = serializers.DecimalField(write_only=True, required=False, max_digits=10, decimal_places=2, allow_null=True)

    class Meta:
        model = User
        fields = (
            'id',
            'username',
            'email',
            'password',
            'full_name',
            'phone',
            'state',
            'identity',
            'interest1',
            'interest2',
            'interest3',
            'role',
            'area_of_expertise',
            'bio',
            'experience',
            'hourly_rate',
        )
        read_only_fields = ('id',)

    def validate_email(self, value):
        if User.objects.filter(email__iexact=value).exists():
            raise serializers.ValidationError('A user with this email already exists.')
        return value

    def create(self, validated_data):
        consultant_data = {
            'area_of_expertise': validated_data.pop('area_of_expertise', ''),
            'bio': validated_data.pop('bio', ''),
            'experience': validated_data.pop('experience', ''),
            'hourly_rate': validated_data.pop('hourly_rate', None),
            'identity': validated_data.get('identity', ''),
            'state': validated_data.get('state', ''),
        }
        password = validated_data.pop('password')
        user = User(**validated_data)
        user.set_password(password)
        user.save()
        if user.role == User.Role.CONSULTANT:
            ConsultantProfile.objects.create(user=user, **consultant_data)
        return user


class EmailOrUsernameTokenObtainPairSerializer(TokenObtainPairSerializer):
    login = serializers.CharField(required=False, write_only=True)
    email = serializers.EmailField(required=False, write_only=True)
    role = serializers.CharField(required=False, write_only=True)

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.fields[self.username_field].required = False

    def validate(self, attrs):
        login = attrs.get('login') or attrs.get('email') or attrs.get(self.username_field)
        if not login:
            raise serializers.ValidationError({'email': 'Email or username is required.'})

        user = (
            User.objects.filter(email__iexact=login).first()
            or User.objects.filter(username__iexact=login).first()
        )
        if not user:
            raise serializers.ValidationError({'email': 'No account found with those credentials.'})

        requested_role = attrs.get('role')
        if requested_role and user.role != requested_role:
            raise serializers.ValidationError({'role': 'This account does not match the selected login role.'})

        attrs[self.username_field] = user.get_username()
        data = super().validate(attrs)
        data['user'] = UserSerializer(user, context=self.context).data
        return data
