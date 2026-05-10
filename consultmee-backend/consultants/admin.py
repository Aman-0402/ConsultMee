from django.contrib import admin
from .models import ConsultantProfile, ConsultantRating


@admin.register(ConsultantProfile)
class ConsultantProfileAdmin(admin.ModelAdmin):
    list_display = ('user', 'area_of_expertise', 'state', 'hourly_rate', 'is_verified')
    search_fields = ('user__username', 'user__full_name', 'area_of_expertise', 'state')
    list_filter = ('is_verified', 'state')


@admin.register(ConsultantRating)
class ConsultantRatingAdmin(admin.ModelAdmin):
    list_display = ('consultant', 'user', 'rating', 'created_at')
    list_filter = ('rating',)

# Register your models here.
