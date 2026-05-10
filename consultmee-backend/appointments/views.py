from rest_framework import filters, viewsets
from .models import Appointment
from .serializers import AppointmentSerializer


class AppointmentViewSet(viewsets.ModelViewSet):
    queryset = Appointment.objects.select_related('user', 'consultant', 'consultant__user').all()
    serializer_class = AppointmentSerializer
    filter_backends = (filters.SearchFilter,)
    search_fields = ('user__username', 'consultant__user__username', 'status')

# Create your views here.
