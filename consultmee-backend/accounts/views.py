from django.contrib.auth import get_user_model
from rest_framework import generics, permissions, viewsets
from .serializers import RegisterSerializer, UserSerializer

User = get_user_model()


class RegisterView(generics.CreateAPIView):
    queryset = User.objects.all()
    serializer_class = RegisterSerializer
    permission_classes = (permissions.AllowAny,)


class UserViewSet(viewsets.ModelViewSet):
    queryset = User.objects.all().order_by('-date_joined')
    serializer_class = UserSerializer

    def get_permissions(self):
        if self.action in ('create',):
            return [permissions.AllowAny()]
        return [permissions.IsAuthenticated()]

# Create your views here.
