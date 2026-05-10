"""
URL configuration for config project.

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/4.2/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  path('', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  path('', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.urls import include, path
    2. Add a URL to urlpatterns:  path('blog/', include('blog.urls'))
"""
from django.conf import settings
from django.conf.urls.static import static
from django.contrib import admin
from django.urls import include, path
from rest_framework.routers import DefaultRouter
from rest_framework_simplejwt.views import TokenObtainPairView, TokenRefreshView
from accounts.views import RegisterView, UserViewSet
from appointments.views import AppointmentViewSet
from categories.views import CategoryViewSet
from consultants.views import ConsultantProfileViewSet, ConsultantRatingViewSet
from contact.views import ContactMessageViewSet
from projects.views import ProjectApplicationViewSet, ProjectViewSet

router = DefaultRouter()
router.register('users', UserViewSet, basename='user')
router.register('consultants', ConsultantProfileViewSet, basename='consultant')
router.register('consultant-ratings', ConsultantRatingViewSet, basename='consultant-rating')
router.register('categories', CategoryViewSet, basename='category')
router.register('appointments', AppointmentViewSet, basename='appointment')
router.register('projects', ProjectViewSet, basename='project')
router.register('project-applications', ProjectApplicationViewSet, basename='project-application')
router.register('contact-messages', ContactMessageViewSet, basename='contact-message')

urlpatterns = [
    path('admin/', admin.site.urls),
    path('api/auth/register/', RegisterView.as_view(), name='register'),
    path('api/auth/token/', TokenObtainPairView.as_view(), name='token_obtain_pair'),
    path('api/auth/token/refresh/', TokenRefreshView.as_view(), name='token_refresh'),
    path('api/', include(router.urls)),
] + static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
