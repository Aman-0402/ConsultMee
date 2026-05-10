import { Link } from 'react-router-dom'
import logo from '../../assets/brand/logo.png'

function Footer() {
  return (
    <footer className="bg-indigo-50 py-12 text-slate-800">
      <div className="mx-auto grid max-w-7xl gap-8 px-4 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div>
          <img src={logo} alt="ConsultME" className="h-16 w-auto" />
          <p className="mt-4 text-sm leading-6">ConsultME: Your partner in smart, AI-powered business consulting. Accelerate your growth with innovation-driven solutions.</p>
        </div>
        <div>
          <h3 className="text-sm font-black text-emerald-600">Quick Links</h3>
          <div className="mt-4 flex flex-col gap-3 text-sm font-semibold">
            <Link to="/about">About Us</Link>
            <Link to="/contact">Contact Us</Link>
            <Link to="/services">Our Services</Link>
          </div>
        </div>
        <div>
          <h3 className="text-sm font-black text-emerald-600">Get Involved</h3>
          <div className="mt-4 flex flex-col gap-3 text-sm font-semibold">
            <Link to="/signup/consultant">Become a Freelancer</Link>
            <Link to="/login/consultant">Freelancer Login</Link>
            <Link to="/contact">Contact</Link>
          </div>
        </div>
        <div>
          <h3 className="text-sm font-black text-emerald-600">Contact Us</h3>
          <address className="mt-4 text-sm not-italic leading-6">
            2066 2nd Floor, Nazarbaug Palace, Mandvi, Near Mandvi Gate, Vadodara, Gujarat, India 390001
          </address>
          <p className="mt-3 text-sm">+91 8317818107</p>
          <p className="mt-1 text-sm">info@consultmee.in</p>
        </div>
      </div>
      <div className="mx-auto mt-10 max-w-7xl border-t border-slate-200 px-4 pt-6 text-center text-xs font-semibold text-slate-500 lg:px-8">
        © ConsultME, All rights reserved.
      </div>
    </footer>
  )
}

export default Footer
