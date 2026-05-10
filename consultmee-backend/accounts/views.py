from django.contrib.auth import get_user_model
from rest_framework import generics, permissions, response, views, viewsets
from rest_framework_simplejwt.views import TokenObtainPairView
from .serializers import EmailOrUsernameTokenObtainPairSerializer, RegisterSerializer, UserSerializer

User = get_user_model()


class RegisterView(generics.CreateAPIView):
    queryset = User.objects.all()
    serializer_class = RegisterSerializer
    permission_classes = (permissions.AllowAny,)


class LoginView(TokenObtainPairView):
    serializer_class = EmailOrUsernameTokenObtainPairSerializer


class MeView(views.APIView):
    permission_classes = (permissions.IsAuthenticated,)

    def get(self, request):
        return response.Response(UserSerializer(request.user, context={'request': request}).data)


class UserViewSet(viewsets.ModelViewSet):
    queryset = User.objects.all().order_by('-date_joined')
    serializer_class = UserSerializer

    def get_permissions(self):
        if self.action in ('create',):
            return [permissions.AllowAny()]
        return [permissions.IsAuthenticated()]

# Create your views here.
