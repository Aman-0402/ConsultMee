import { useState } from 'react'
import { NavLink, Link } from 'react-router-dom'
import { FaBars, FaXmark } from 'react-icons/fa6'
import logo from '../../assets/brand/logo.png'
import { navLinks } from '../../data'
import Button from '../ui/Button'

function Header() {
  const [open, setOpen] = useState(false)

  return (
    <header className="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
      <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 lg:px-8">
        <Link to="/" className="flex items-center">
          <img src={logo} alt="ConsultME" className="h-14 w-auto object-contain" />
        </Link>

        <nav className="hidden items-center gap-8 md:flex">
          {navLinks.map((link) => (
            <NavLink
              key={link.to}
              to={link.to}
              className={({ isActive }) =>
                `text-sm font-bold transition ${isActive ? 'text-blue-700' : 'text-slate-700 hover:text-blue-700'}`
              }
            >
              {link.label}
            </NavLink>
          ))}
        </nav>

        <div className="hidden items-center gap-3 md:flex">
          <Button to="/create-account" variant="secondary" className="px-4 py-2">
            Signup
          </Button>
          <Button to="/login-account" variant="dark" className="px-4 py-2">
            Login
          </Button>
        </div>

        <button
          type="button"
          aria-label="Toggle navigation"
          onClick={() => setOpen((value) => !value)}
          className="rounded-lg border border-slate-200 p-3 text-slate-900 md:hidden"
        >
          {open ? <FaXmark /> : <FaBars />}
        </button>
      </div>

      {open ? (
        <div className="border-t border-slate-200 bg-white px-4 py-4 md:hidden">
          <nav className="flex flex-col gap-2">
            {navLinks.map((link) => (
              <NavLink key={link.to} to={link.to} onClick={() => setOpen(false)} className="rounded-lg px-3 py-3 text-sm font-bold text-slate-700 hover:bg-slate-100">
                {link.label}
              </NavLink>
            ))}
          </nav>
          <div className="mt-4 grid grid-cols-2 gap-3">
            <Button to="/create-account" variant="secondary" className="py-2" onClick={() => setOpen(false)}>
              Signup
            </Button>
            <Button to="/login-account" variant="dark" className="py-2" onClick={() => setOpen(false)}>
              Login
            </Button>
          </div>
        </div>
      ) : null}
    </header>
  )
}

export default Header
