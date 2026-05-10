import { Outlet } from 'react-router-dom'
import Footer from '../components/marketing/Footer'
import Header from '../components/marketing/Header'

function PublicLayout() {
  return (
    <div className="min-h-screen bg-slate-50 text-slate-950">
      <Header />
      <main>
        <Outlet />
      </main>
      <Footer />
    </div>
  )
}

export default PublicLayout
