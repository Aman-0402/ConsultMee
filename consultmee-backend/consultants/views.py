from rest_framework import filters, viewsets
from .models import ConsultantProfile, ConsultantRating
from .serializers import ConsultantProfileSerializer, ConsultantRatingSerializer


class ConsultantProfileViewSet(viewsets.ModelViewSet):
    queryset = ConsultantProfile.objects.select_related('user').all()
    serializer_class = ConsultantProfileSerializer
    filter_backends = (filters.SearchFilter,)
    search_fields = ('user__full_name', 'user__username', 'area_of_expertise', 'bio', 'state')


class ConsultantRatingViewSet(viewsets.ModelViewSet):
    queryset = ConsultantRating.objects.select_related('consultant', 'user').all()
    serializer_class = ConsultantRatingSerializer

# Create your views here.
