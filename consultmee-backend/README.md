# ConsultME Django Backend

Frontend lives in `../consultmee-react`.

## Run locally

```bash
python manage.py migrate
python manage.py createsuperuser
python manage.py runserver
```

## API roots

- `POST /api/auth/register/`
- `POST /api/auth/token/`
- `POST /api/auth/token/refresh/`
- `/api/users/`
- `/api/consultants/`
- `/api/categories/`
- `/api/appointments/`
- `/api/projects/`
- `/api/project-applications/`
- `/api/contact-messages/`
