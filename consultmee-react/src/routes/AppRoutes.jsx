import { createBrowserRouter } from 'react-router-dom'
import DashboardLayout from '../layouts/DashboardLayout'
import PublicLayout from '../layouts/PublicLayout'
import AuthChoicePage from '../pages/auth/AuthChoicePage'
import PlaceholderPage from '../pages/PlaceholderPage'
import DashboardHome from '../pages/dashboard/DashboardHome'
import HomePage from '../pages/marketing/HomePage'
import ServicesPage from '../pages/marketing/ServicesPage'

export const router = createBrowserRouter([
  {
    element: <PublicLayout />,
    children: [
      { path: '/', element: <HomePage /> },
      { path: '/services', element: <ServicesPage /> },
      { path: '/about', element: <PlaceholderPage title="About ConsultME" /> },
      { path: '/contact', element: <PlaceholderPage title="Contact ConsultME" /> },
      { path: '/create-account', element: <AuthChoicePage mode="signup" /> },
      { path: '/login-account', element: <AuthChoicePage mode="login" /> },
      { path: '/signup/:role', element: <PlaceholderPage title="Signup Flow" /> },
      { path: '/login/:role', element: <PlaceholderPage title="Login Flow" /> },
    ],
  },
  {
    path: '/dashboard',
    element: <DashboardLayout />,
    children: [{ index: true, element: <DashboardHome /> }],
  },
])
