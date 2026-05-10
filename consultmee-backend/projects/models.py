from django.conf import settings
from django.db import models


class Project(models.Model):
    class Status(models.TextChoices):
        DRAFT = 'draft', 'Draft'
        ACTIVE = 'active', 'Active'
        CLOSED = 'closed', 'Closed'

    owner = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='projects')
    project_id = models.CharField(max_length=40, unique=True)
    title = models.CharField(max_length=220)
    short_description = models.CharField(max_length=512, blank=True)
    description = models.TextField()
    budget = models.CharField(max_length=120, blank=True)
    billing = models.CharField(max_length=60, blank=True)
    expiry_date = models.DateField(null=True, blank=True)
    links = models.TextField(blank=True)
    status = models.CharField(max_length=24, choices=Status.choices, default=Status.ACTIVE)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        ordering = ('-created_at',)

    def __str__(self):
        return self.title


class ProjectApplication(models.Model):
    project = models.ForeignKey(Project, on_delete=models.CASCADE, related_name='applications')
    consultant = models.ForeignKey('consultants.ConsultantProfile', on_delete=models.CASCADE, related_name='project_applications')
    user_msg = models.TextField(blank=True)
    portfolio_link = models.URLField(blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        unique_together = ('project', 'consultant')
        ordering = ('-created_at',)

    def __str__(self):
        return f'{self.consultant} -> {self.project}'

# Create your models here.
