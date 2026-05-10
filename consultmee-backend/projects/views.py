from rest_framework import filters, viewsets
from .models import Project, ProjectApplication
from .serializers import ProjectApplicationSerializer, ProjectSerializer


class ProjectViewSet(viewsets.ModelViewSet):
    queryset = Project.objects.select_related('owner').all()
    serializer_class = ProjectSerializer
    filter_backends = (filters.SearchFilter,)
    search_fields = ('project_id', 'title', 'short_description', 'description', 'status')


class ProjectApplicationViewSet(viewsets.ModelViewSet):
    queryset = ProjectApplication.objects.select_related('project', 'consultant', 'consultant__user').all()
    serializer_class = ProjectApplicationSerializer

# Create your views here.
