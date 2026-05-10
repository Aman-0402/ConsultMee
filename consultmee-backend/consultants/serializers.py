from rest_framework import serializers
from .models import ConsultantProfile, ConsultantRating


class ConsultantProfileSerializer(serializers.ModelSerializer):
    username = serializers.CharField(source='user.username', read_only=True)
    email = serializers.EmailField(source='user.email', read_only=True)
    name = serializers.CharField(source='user.full_name', read_only=True)

    class Meta:
        model = ConsultantProfile
        fields = '__all__'


class ConsultantRatingSerializer(serializers.ModelSerializer):
    class Meta:
        model = ConsultantRating
        fields = '__all__'
