function FeatureCard({ icon: Icon, title, description }) {
  return (
    <article className="h-full rounded-lg border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-slate-200/70">
      <div className="mb-5 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-xl text-blue-600">
        <Icon />
      </div>
      <h3 className="text-lg font-black text-slate-950">{title}</h3>
      <p className="mt-3 text-sm leading-6 text-slate-600">{description}</p>
    </article>
  )
}

export default FeatureCard
