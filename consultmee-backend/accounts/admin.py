from django.contrib import admin
from django.contrib.auth.admin import UserAdmin
from .models import User


@admin.register(User)
class ConsultMeeUserAdmin(UserAdmin):
    fieldsets = UserAdmin.fieldsets + (
        ('ConsultME Profile', {'fields': ('role', 'full_name', 'phone', 'state', 'identity', 'interest1', 'interest2', 'interest3', 'profile_img')}),
    )
    list_display = ('username', 'email', 'full_name', 'role', 'is_staff')

# Register your models here.
