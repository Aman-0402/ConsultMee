from django.contrib.auth.models import AbstractUser
from django.db import models


class User(AbstractUser):
    class Role(models.TextChoices):
        SOLUTION_SEEKER = 'solution_seeker', 'Solution Seeker'
        CONSULTANT = 'consultant', 'Consultant'
        ADMIN = 'admin', 'Admin'

    role = models.CharField(max_length=32, choices=Role.choices, default=Role.SOLUTION_SEEKER)
    full_name = models.CharField(max_length=150, blank=True)
    phone = models.CharField(max_length=30, blank=True)
    state = models.CharField(max_length=100, blank=True)
    identity = models.CharField(max_length=80, blank=True)
    interest1 = models.CharField(max_length=120, blank=True)
    interest2 = models.CharField(max_length=120, blank=True)
    interest3 = models.CharField(max_length=120, blank=True)
    profile_img = models.ImageField(upload_to='users/', blank=True, null=True)

    def __str__(self):
        return self.full_name or self.username

# Create your models here.
