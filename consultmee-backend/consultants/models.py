from django.conf import settings
from django.db import models


class ConsultantProfile(models.Model):
    user = models.OneToOneField(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='consultant_profile')
    area_of_expertise = models.CharField(max_length=160, blank=True)
    bio = models.TextField(blank=True)
    experience = models.TextField(blank=True)
    hourly_rate = models.DecimalField(max_digits=10, decimal_places=2, null=True, blank=True)
    identity = models.CharField(max_length=80, blank=True)
    state = models.CharField(max_length=100, blank=True)
    profile_img = models.ImageField(upload_to='consultants/', blank=True, null=True)
    is_verified = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        ordering = ('-created_at',)

    def __str__(self):
        return self.user.get_full_name() or self.user.username


class ConsultantRating(models.Model):
    consultant = models.ForeignKey(ConsultantProfile, on_delete=models.CASCADE, related_name='ratings')
    user = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='given_consultant_ratings')
    rating = models.PositiveSmallIntegerField()
    feedback = models.TextField(blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        unique_together = ('consultant', 'user')
        ordering = ('-created_at',)

    def __str__(self):
        return f'{self.consultant} - {self.rating}'

# Create your models here.
