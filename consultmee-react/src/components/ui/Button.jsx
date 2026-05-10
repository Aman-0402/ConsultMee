import { Link } from 'react-router-dom'

const variants = {
  primary: 'bg-blue-600 text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700',
  secondary: 'border border-slate-300 bg-white text-slate-900 hover:border-blue-300 hover:text-blue-700',
  dark: 'bg-slate-950 text-white hover:bg-slate-800',
}

function Button({ children, to, variant = 'primary', className = '', ...props }) {
  const classes = `inline-flex items-center justify-center gap-2 rounded-lg px-5 py-3 text-sm font-bold transition ${variants[variant]} ${className}`

  if (to) {
    return (
      <Link to={to} className={classes} {...props}>
        {children}
      </Link>
    )
  }

  return (
    <button type="button" className={classes} {...props}>
      {children}
    </button>
  )
}

export default Button
