from django.contrib import admin
from .models import Project, ProjectApplication


@admin.register(Project)
class ProjectAdmin(admin.ModelAdmin):
    list_display = ('project_id', 'title', 'owner', 'budget', 'status', 'expiry_date')
    list_filter = ('status', 'billing')
    search_fields = ('project_id', 'title', 'description')


@admin.register(ProjectApplication)
class ProjectApplicationAdmin(admin.ModelAdmin):
    list_display = ('project', 'consultant', 'created_at')
    search_fields = ('project__title', 'consultant__user__username')

# Register your models here.
