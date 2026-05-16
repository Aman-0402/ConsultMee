// ===================== Global State =====================
let allAppointments = [];
let currentEditId = null;

// For consultant project browsing
let consultantProjectsById = {};
let currentProjectId = null;
let projectModalInitialized = false;

// Escape helper
function escapeHtml(s) {
  if (!s) return '';
  return String(s).replace(/[&<>"']/g, c => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
  })[c]);
}

// ===================== Section Navigation =====================
function showSection(id) {
  const sections = ['profile', 'appointments', 'requests', 'history', 'consultant_projects'];
  sections.forEach(sec => {
    const el = document.getElementById(sec);
    if (el) el.style.display = sec === id ? 'block' : 'none';
  });

  const pageTitle = document.getElementById("page-title");
  if (pageTitle) pageTitle.textContent = id.charAt(0).toUpperCase() + id.slice(1);

  document.querySelectorAll(".sidebar a").forEach(link => link.classList.remove("active"));
  const activeLink = document.querySelector(`.sidebar a[href="#${id}"]`);
  if (activeLink) activeLink.classList.add("active");

  if (id === 'appointments') fetchConfirmedAppointments();
  if (id === 'requests') fetchAppointments();
  if (id === 'history') fetchHistory();
  if (id === 'profile') fetchConsultantProfile();
  if (id === 'consultant_projects') showConsultantProjects('', 1);
}

// ===================== Helpers =====================
function formatTime(mysqlTime) {
  if (!mysqlTime) return '-';
  const parts = mysqlTime.split(':');
  if (parts.length >= 2) return parts[0].padStart(2, '0') + ':' + parts[1].padStart(2, '0');
  return mysqlTime;
}

// ===================== Appointment Cards =====================
function renderAppointmentCard(row) {
    return `
          <div class="col-md-6 mb-4">
            <div class="appointment-card border-0 p-3 h-100 rounded-4 d-flex align-items-stretch"
                 style="
                   background: rgba(255,255,255,0.92);
                   backdrop-filter: blur(14px);
                   border: 1px solid rgba(15,23,42,0.10);
                   box-shadow: 0 18px 55px rgba(15,23,42,0.10);
                   transition: 0.25s ease;
                   overflow: hidden;
                   position: relative;
                 "
                 data-appointment-id="${escapeHtml(row.appointment_id || '')}">
        
              <!-- Premium Glow Background -->
              <div style="
                position:absolute;
                inset:0;
                background:
                  radial-gradient(circle at top left, rgba(37,99,235,0.16), transparent 60%),
                  radial-gradient(circle at bottom right, rgba(15,23,42,0.10), transparent 65%);
                opacity: 0.85;
                pointer-events:none;
                z-index:0;
              "></div>
        
              <!-- Left: Profile -->
              <div class="text-center me-3 d-flex flex-column align-items-center justify-content-center"
                   style="min-width: 150px; position:relative; z-index:2;">
        
                <div class="position-relative d-inline-block mb-2">
                  <img src="/uploads/users/${escapeHtml(row.profile_img || 'default.png')}"
                       class="rounded-circle"
                       alt="User" width="112" height="112"
                       style="
                         object-fit: cover;
                         border: 5px solid rgba(255,255,255,0.95);
                         box-shadow: 0 16px 40px rgba(15,23,42,0.18);
                         transition: 0.25s ease;
                       ">
        
                  <span class="position-absolute bottom-0 end-0 translate-middle p-2 rounded-circle border border-white"
                        style="background: linear-gradient(135deg,#22c55e,#16a34a); box-shadow:0 10px 25px rgba(34,197,94,0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="white" class="bi bi-check" viewBox="0 0 16 16">
                      <path d="M13.485 1.929a.75.75 0 0 1 1.03 1.09l-7.25 8a.75.75 0 0 1-1.08.02L1.485 6.616a.75.75 0 1 1 1.03-1.09l4.202 3.968 6.768-7.565z"/>
                    </svg>
                  </span>
                </div>
        
                <h6 class="fw-bold mb-1"
                    style="color:#0f172a; font-weight:900; letter-spacing:-0.3px;">
                  ${escapeHtml(row.users_name || 'User Name')}
                </h6>
        
                <span class="badge rounded-pill px-3 py-2"
                      style="
                        background: linear-gradient(90deg,#0d6efd,#2563eb,#0ea5e9);
                        color:#fff;
                        font-weight:900;
                        font-size:0.78rem;
                        letter-spacing:0.4px;
                        box-shadow: 0 14px 35px rgba(37,99,235,0.22);
                      ">
                  <em style="font-style:normal;">${escapeHtml(row.status || 'Status')}</em>
                </span>
              </div>
        
              <!-- Right: Info -->
              <div class="flex-grow-1 d-flex flex-column"
                   style="position:relative; z-index:2;">
        
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h6 class="mb-0 text-muted small fw-bold"
                      style="letter-spacing:0.6px; text-transform:uppercase;">
                    Appointment ID
                  </h6>
        
                  <span class="fw-bold"
                        style="color:#0f172a; font-size:0.95rem;">
                    #${escapeHtml(row.appointment_id || '-')}
                  </span>
                </div>
        
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="badge rounded-pill px-3 py-2"
                        style="
                          background: rgba(255,255,255,0.75);
                          border: 1px solid rgba(15,23,42,0.10);
                          color:#0f172a;
                          font-weight:800;
                          box-shadow: 0 10px 22px rgba(15,23,42,0.06);
                        ">
                    <i class="bi bi-calendar-event me-1 text-primary"></i>
                    <em style="font-style:normal;">${escapeHtml(row.appointment_date || '(Date)')}</em>
                  </span>
        
                  <span class="badge rounded-pill px-3 py-2"
                        style="
                          background: rgba(255,255,255,0.75);
                          border: 1px solid rgba(15,23,42,0.10);
                          color:#0f172a;
                          font-weight:800;
                          box-shadow: 0 10px 22px rgba(15,23,42,0.06);
                        ">
                    <i class="bi bi-clock me-1 text-primary"></i>
                    <em style="font-style:normal;">${escapeHtml(formatTime(row.appointment_time) || '(Time)')}</em>
                  </span>
        
                  <span class="badge rounded-pill px-3 py-2"
                        style="
                          background: rgba(255,255,255,0.75);
                          border: 1px solid rgba(15,23,42,0.10);
                          color:#0f172a;
                          font-weight:800;
                          box-shadow: 0 10px 22px rgba(15,23,42,0.06);
                        ">
                    <i class="bi bi-geo-alt me-1 text-primary"></i>
                    ${escapeHtml(row.mode_of_appointment || 'Location')}
                  </span>
                </div>
        
                <div class="rounded-4 p-3 mb-3 small"
                     style="
                       background: rgba(15,23,42,0.04);
                       border: 1px solid rgba(15,23,42,0.08);
                     ">
                  <div class="fw-bold mb-1"
                       style="color:#0f172a;">
                    <i class="bi bi-chat-left-text me-2 text-primary"></i>
                    Request from user
                  </div>
        
                  <div style="color:#334155; font-weight:600; line-height:1.6;">
                    ${escapeHtml(row.user_request || 'Anything......')}
                  </div>
                </div>
        
                <div class="mt-auto d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <div class="small fw-bold" style="color:#0f172a;">
                    <i class="bi bi-link-45deg text-primary me-1"></i>
                    Meeting Link:
                    ${
                      row.meeting_link
                        ? `<a href="${escapeHtml(row.meeting_link)}" target="_blank"
                             class="btn btn-sm ms-2 rounded-pill"
                             style="
                               font-weight:900;
                               border: 1px solid rgba(37,99,235,0.28);
                               background: rgba(255,255,255,0.75);
                               color:#2563eb;
                               padding: 0.4rem 0.95rem;
                               transition:0.22s ease;
                             ">
                             <i class="bi bi-camera-video me-1"></i> Join
                           </a>`
                        : '<span class="text-muted ms-1 fw-semibold">N/A</span>'
                    }
                  </div>
        
                  <button class="btn btn-sm rounded-pill px-4 edit-appointment-btn"
                          style="
                            font-weight:900;
                            background: linear-gradient(90deg,#0f172a,#111827,#1f2937);
                            color:white;
                            border:none;
                            box-shadow: 0 14px 35px rgba(15,23,42,0.18);
                            transition:0.22s ease;
                          "
                          data-appointment-id="${escapeHtml(row.appointment_id || '')}">
                    <i class="bi bi-pencil-square me-2"></i>Edit
                  </button>
                </div>
        
              </div>
            </div>
          </div>
        
          <script>
            document.querySelectorAll('.appointment-card').forEach(card => {
              card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-6px)';
                card.style.boxShadow = '0 28px 80px rgba(15,23,42,0.14)';
                card.style.borderColor = 'rgba(37,99,235,0.22)';
              });
              card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0px)';
                card.style.boxShadow = '0 18px 55px rgba(15,23,42,0.10)';
                card.style.borderColor = 'rgba(15,23,42,0.10)';
              });
            });
          </script>
    `;

}

// ===================== Appointments: Requests =====================
function fetchAppointments() {
  fetch('/api/consultant/appointments/requests', { credentials: 'same-origin' })
    .then(response => {
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      return response.json().catch(() => { throw new Error('Invalid JSON from consultant_appointments.php'); });
    })
    .then(data => {
      const container = document.getElementById("appointments_list");
      if (!container) {
        console.error("❌ appointments_list container not found in DOM");
        return;
      }

      if (!Array.isArray(data)) {
        container.innerHTML = `<div class="alert alert-danger">Error: ${escapeHtml(data.error || 'Unknown error')}</div>`;
        return;
      }

      allAppointments = data;

      if (data.length === 0) {
        container.innerHTML = `<div class="alert alert-info text-center">No appointment requests found.</div>`;
        return;
      }

      container.innerHTML = `<div class="row">${data.map(renderAppointmentCard).join('')}</div>`;
      bindEditButtons();
      ensureModalExists();
    })
    .catch(error => {
      console.error("❌ Error fetching appointments:", error);
      const container = document.getElementById("appointments_list");
      if (container) {
        container.innerHTML = `
          <div class="alert alert-danger">
            Failed to fetch appointment requests.<br>
            <small>${escapeHtml(error.message)}</small>
          </div>`;
      }
    });
}

function bindEditButtons() {
  document.querySelectorAll('.edit-appointment-btn').forEach(button => {
    button.addEventListener('click', () => {
      const id = button.dataset.appointmentId;
      const app = allAppointments.find(a => a.appointment_id == id);
      if (app) editAppointment(app);
    });
  });
}

function ensureModalExists() {
  if (!document.getElementById('editAppointmentModal')) {
    document.body.insertAdjacentHTML('beforeend', `
        <div class="modal fade" id="editAppointmentModal" tabindex="-1" aria-labelledby="editAppointmentModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content rounded-4 border-0 overflow-hidden"
                 style="
                   background: rgba(255,255,255,0.94);
                   backdrop-filter: blur(14px);
                   box-shadow: 0 28px 85px rgba(15,23,42,0.20);
                 ">
        
              <!-- HEADER -->
              <div class="modal-header border-0 px-4 py-4"
                   style="
                     background: linear-gradient(135deg,
                       rgba(37,99,235,0.14) 0%,
                       rgba(14,165,233,0.10) 40%,
                       rgba(255,255,255,0.95) 100%
                     );
                     border-bottom: 1px solid rgba(15,23,42,0.08);
                   ">
        
                <div class="d-flex align-items-center gap-3">
                  <div class="rounded-4 d-flex align-items-center justify-content-center"
                       style="
                         width: 48px;
                         height: 48px;
                         background: linear-gradient(135deg,#0d6efd,#2563eb,#0ea5e9);
                         box-shadow: 0 16px 40px rgba(37,99,235,0.25);
                         color: white;
                         font-size: 18px;
                       ">
                    <i class="bi bi-pencil-square"></i>
                  </div>
        
                  <div>
                    <h5 class="modal-title fw-bold mb-0"
                        style="color:#0f172a; letter-spacing:-0.4px;">
                      Edit Appointment
                    </h5>
                    <small class="text-muted fw-semibold">
                      Update appointment details & meeting information
                    </small>
                  </div>
                </div>
        
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
              </div>
        
              <!-- BODY -->
              <div class="modal-body px-4 pt-4 pb-3">
                <form id="editAppointmentForm" class="row g-3">
        
                  <input type="hidden" id="edit-id">
        
                  <!-- Date -->
                  <div class="col-md-6">
                    <label class="form-label fw-bold small"
                           style="color:#0f172a;">
                      <i class="bi bi-calendar-event text-primary me-1"></i>Date *
                    </label>
                    <input type="date" id="edit-date"
                           class="form-control rounded-4"
                           style="
                             padding: 0.85rem 1rem;
                             font-weight: 700;
                             border: 1px solid rgba(15,23,42,0.14);
                             background: rgba(255,255,255,0.95);
                           ">
                  </div>
        
                  <!-- Time -->
                  <div class="col-md-6">
                    <label class="form-label fw-bold small"
                           style="color:#0f172a;">
                      <i class="bi bi-clock text-primary me-1"></i>Time *
                    </label>
                    <input type="time" id="edit-time"
                           class="form-control rounded-4"
                           style="
                             padding: 0.85rem 1rem;
                             font-weight: 700;
                             border: 1px solid rgba(15,23,42,0.14);
                             background: rgba(255,255,255,0.95);
                           ">
                  </div>
        
                  <!-- Mode -->
                  <div class="col-md-6">
                    <label class="form-label fw-bold small"
                           style="color:#0f172a;">
                      <i class="bi bi-geo-alt text-primary me-1"></i>Mode *
                    </label>
                    <select id="edit-mode"
                            class="form-select rounded-4"
                            style="
                              padding: 0.85rem 1rem;
                              font-weight: 800;
                              border: 1px solid rgba(15,23,42,0.14);
                              background: rgba(255,255,255,0.95);
                            ">
                      <option>Online</option>
                      <option>Physical</option>
                    </select>
                  </div>
        
                  <!-- Status -->
                  <div class="col-md-6">
                    <label class="form-label fw-bold small"
                           style="color:#0f172a;">
                      <i class="bi bi-shield-check text-primary me-1"></i>Status *
                    </label>
                    <select id="edit-status"
                            class="form-select rounded-4"
                            style="
                              padding: 0.85rem 1rem;
                              font-weight: 800;
                              border: 1px solid rgba(15,23,42,0.14);
                              background: rgba(255,255,255,0.95);
                            ">
                      <option>Pending</option>
                      <option>Confirmed</option>
                      <option>Completed</option>
                      <option>Cancelled</option>
                    </select>
                  </div>
        
                  <!-- Meeting Link -->
                  <div class="col-12">
                    <label class="form-label fw-bold small"
                           style="color:#0f172a;">
                      <i class="bi bi-link-45deg text-primary me-1"></i>Meeting Link *
                    </label>
                    <input type="url" id="edit-link"
                           class="form-control rounded-4"
                           placeholder="https://..."
                           style="
                             padding: 0.9rem 1rem;
                             font-weight: 700;
                             border: 1px solid rgba(15,23,42,0.14);
                             background: rgba(255,255,255,0.95);
                           ">
                  </div>
        
                  <!-- Message -->
                  <div class="col-12">
                    <label class="form-label fw-bold small"
                           style="color:#0f172a;">
                      <i class="bi bi-chat-left-text text-primary me-1"></i>Consultant Message
                    </label>
                    <textarea id="edit-message"
                              class="form-control rounded-4"
                              rows="3"
                              placeholder="Enter message to user *"
                              style="
                                padding: 0.9rem 1rem;
                                font-weight: 650;
                                border: 1px solid rgba(15,23,42,0.14);
                                background: rgba(255,255,255,0.95);
                                resize: none;
                              "></textarea>
                  </div>
        
                </form>
              </div>
        
              <!-- FOOTER -->
              <div class="modal-footer border-0 px-4 pb-4 pt-2 d-flex gap-2">
        
                <button type="button"
                        class="btn rounded-pill px-4 py-2 fw-bold"
                        data-bs-dismiss="modal"
                        style="
                          background: rgba(15,23,42,0.06);
                          border: 1px solid rgba(15,23,42,0.16);
                          color: #0f172a;
                        ">
                  <i class="bi bi-x-circle me-1"></i> Close
                </button>
        
                <button type="button"
                        class="btn rounded-pill px-4 py-2 fw-bold text-white"
                        onclick="saveAppointment()"
                        style="
                          background: linear-gradient(90deg,#0d6efd,#2563eb,#0ea5e9);
                          border: none;
                          box-shadow: 0 18px 45px rgba(37,99,235,0.25);
                        ">
                  <i class="bi bi-check-circle me-1"></i> Save Changes
                </button>
        
              </div>
        
            </div>
          </div>
        </div>

    `);
  }
}

function editAppointment(app) {
  currentEditId = app.appointment_id;
  const el = id => document.getElementById(id);
  if (!el("edit-id")) ensureModalExists();

  document.getElementById("edit-id").value = app.appointment_id;
  document.getElementById("edit-date").value = app.appointment_date;
  document.getElementById("edit-time").value = app.appointment_time ? app.appointment_time.split('.')[0].slice(0, 5) : '';
  document.getElementById("edit-mode").value = app.mode_of_appointment || 'Online';
  document.getElementById("edit-status").value = app.status || 'Pending';
  document.getElementById("edit-link").value = app.meeting_link || '';
  document.getElementById("edit-message").value = app.consultant_message || '';

  const modalEl = document.getElementById("editAppointmentModal");
  const modal = new bootstrap.Modal(modalEl);
  modal.show();
}

function saveAppointment() {
  const payload = {
    id: document.getElementById("edit-id").value,
    date: document.getElementById("edit-date").value,
    time: document.getElementById("edit-time").value,
    mode: document.getElementById("edit-mode").value,
    status: document.getElementById("edit-status").value,
    meeting_link: document.getElementById("edit-link").value,
    consultant_message: document.getElementById("edit-message").value
  };

  fetch('/api/consultant/appointments', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
    credentials: 'same-origin'
  })
    .then(res => res.json().catch(() => ({ success: false, error: "Invalid JSON response" })))
    .then(response => {
      if (response.success) {
        const modal = bootstrap.Modal.getInstance(document.getElementById("editAppointmentModal"));
        if (modal) modal.hide();

        const index = allAppointments.findIndex(app => app.appointment_id == payload.id);
        if (index === -1) {
          alert("Appointment not found in memory.");
          return;
        }

        allAppointments[index] = {
          ...allAppointments[index],
          appointment_date: payload.date,
          appointment_time: payload.time,
          mode_of_appointment: payload.mode,
          status: payload.status,
          meeting_link: payload.meeting_link,
          consultant_message: payload.consultant_message
        };

        const cardElement = document.querySelector(`.appointment-card[data-appointment-id="${payload.id}"]`);
        const cardWrapper = cardElement?.closest(".col-md-6");
        if (!cardWrapper) {
          alert("Card wrapper not found.");
          return;
        }

        const newCardHTML = renderAppointmentCard(allAppointments[index]);
        const temp = document.createElement('div');
        temp.innerHTML = newCardHTML;
        const newCol = temp.firstElementChild;
        cardWrapper.replaceWith(newCol);

        const newEditBtn = newCol.querySelector(`.edit-appointment-btn[data-appointment-id="${payload.id}"]`);
        if (newEditBtn) {
          newEditBtn.addEventListener('click', () => editAppointment(allAppointments[index]));
        }
      } else {
        alert("Update failed: " + (response.error || "Unknown error"));
      }
    })
    .catch(err => {
      console.error("Save error:", err);
      alert("An error occurred while saving.");
    });
}

// ===================== Appointments: Confirmed =====================
function fetchConfirmedAppointments() {
  fetch('/api/consultant/appointments/confirmed', { credentials: 'same-origin' })
    .then(response => {
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      return response.json().catch(() => { throw new Error('Invalid JSON from fetch_confirmed_appointments.php'); });
    })
    .then(data => {
      const container = document.getElementById("appointments_content");
      if (!Array.isArray(data)) {
        container.innerHTML = `<div class="alert alert-danger">Error: ${escapeHtml(data.error || 'Unknown error')}</div>`;
        return;
      }

      allAppointments = data;

      container.innerHTML = data.length > 0
        ? `<div class="row">${data.map(renderAppointmentCard).join('')}</div>`
        : `<div class="alert alert-info text-center">No confirmed appointments found.</div>`;

      bindEditButtons();
      ensureModalExists();
    })
    .catch(err => {
      console.error("Error fetching confirmed appointments:", err);
      document.getElementById("appointments_content").innerHTML = `
        <div class="alert alert-danger">
          Failed to fetch confirmed appointments.<br>
          <small>${escapeHtml(err.message)}</small>
        </div>`;
    });
}

// ===================== History =====================
function fetchHistory() {
  fetch('/api/consultant/appointments/history', { credentials: 'same-origin' })
    .then(response => {
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      return response.json().catch(() => { throw new Error('Invalid JSON from fetch_completed_appointments.php'); });
    })
    .then(data => {
      const container = document.getElementById("history");
      if (!container) {
        console.error("History container not found.");
        return;
      }

      if (!Array.isArray(data)) {
        container.innerHTML = `
          <div class="alert alert-danger rounded-4 shadow-sm border-0">
            <strong>Error:</strong> ${escapeHtml(data.error || 'Unknown error')}
          </div>`;
        return;
      }

      allAppointments = data;

      if (data.length === 0) {
        container.innerHTML = `
          <div class="alert alert-info text-center rounded-4 shadow-sm border-0 fw-semibold">
            No completed or cancelled appointments found.
          </div>`;
        return;
      }

      let tableHTML = `
        <style>
          .history-card {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(15,23,42,0.10);
            border-radius: 22px;
            box-shadow: 0 22px 65px rgba(15,23,42,0.10);
            overflow: hidden;
          }

          .history-card-header {
            padding: 18px 20px;
            background: linear-gradient(135deg,
              rgba(37,99,235,0.14) 0%,
              rgba(14,165,233,0.10) 40%,
              rgba(255,255,255,0.95) 100%
            );
            border-bottom: 1px solid rgba(15,23,42,0.08);
          }

          .history-title {
            font-size: 1.1rem;
            font-weight: 950;
            color: #0f172a;
            letter-spacing: -0.4px;
            margin: 0;
          }

          .history-subtitle {
            font-size: 0.9rem;
            font-weight: 700;
            color: #64748b;
            margin-top: 4px;
          }

          .history-table thead th {
            background: #0f172a !important;
            color: white !important;
            font-weight: 900;
            font-size: 0.85rem;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            padding: 14px 14px;
            border: none !important;
          }

          .history-table tbody td {
            padding: 14px 14px;
            vertical-align: middle;
            font-weight: 700;
            color: #0f172a;
            border-color: rgba(15,23,42,0.08);
          }

          .history-table tbody tr {
            transition: 0.2s ease;
          }

          .history-table tbody tr:hover {
            background: rgba(37,99,235,0.06);
            transform: scale(1.002);
          }

          .badge-status {
            font-weight: 900;
            font-size: 0.78rem;
            padding: 8px 12px;
            border-radius: 999px;
            letter-spacing: 0.4px;
          }

          .badge-completed {
            background: linear-gradient(90deg,#16a34a,#22c55e);
            color: white;
            box-shadow: 0 10px 25px rgba(34,197,94,0.25);
          }

          .badge-cancelled {
            background: linear-gradient(90deg,#dc2626,#ef4444);
            color: white;
            box-shadow: 0 10px 25px rgba(239,68,68,0.25);
          }

          .join-btn {
            font-weight: 900;
            font-size: 0.82rem;
            padding: 7px 14px;
            border-radius: 999px;
            border: 1px solid rgba(37,99,235,0.25);
            background: rgba(255,255,255,0.75);
            color: #2563eb;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: 0.2s ease;
          }

          .join-btn:hover {
            background: linear-gradient(90deg,#0d6efd,#2563eb,#0ea5e9);
            color: white;
            border-color: transparent;
            box-shadow: 0 14px 35px rgba(37,99,235,0.25);
            transform: translateY(-2px);
          }

          .history-muted {
            color: #64748b !important;
            font-weight: 700;
          }

          .history-request-box {
            max-width: 260px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #334155;
            font-weight: 750;
          }

          .history-message-box {
            max-width: 280px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #334155;
            font-weight: 750;
          }
        </style>

        <div class="history-card mb-4">

          <div class="history-card-header">
            <h5 class="history-title">
              <i class="bi bi-clock-history text-primary me-2"></i>
              Appointment History
            </h5>
            <div class="history-subtitle">
              Completed & Cancelled Appointments Overview
            </div>
          </div>

          <div class="table-responsive p-3">
            <table class="table table-hover align-middle small mb-0 history-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>User</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Mode</th>
                  <th>Status</th>
                  <th>Request</th>
                  <th>Meeting Link</th>
                  <th>Consultant Message</th>
                </tr>
              </thead>
              <tbody>
      `;

      data.forEach(row => {
        tableHTML += `
          <tr>
            <td class="fw-bold">#${escapeHtml(row.appointment_id || '-')}</td>
            <td>${escapeHtml(row.users_name || '-')}</td>
            <td>${escapeHtml(row.appointment_date || '-')}</td>
            <td>${escapeHtml(formatTime(row.appointment_time) || '-')}</td>
            <td>
              <span class="badge rounded-pill px-3 py-2"
                    style="background:rgba(15,23,42,0.05); border:1px solid rgba(15,23,42,0.10); color:#0f172a; font-weight:900;">
                ${escapeHtml(row.mode_of_appointment || '-')}
              </span>
            </td>
            <td>
              <span class="badge-status ${
                row.status === 'Completed' ? 'badge-completed' : 'badge-cancelled'
              }">
                ${escapeHtml(row.status)}
              </span>
            </td>
            <td>
              <div class="history-request-box" title="${escapeHtml(row.user_request || '-')}">
                ${escapeHtml(row.user_request || '-')}
              </div>
            </td>
            <td>
              ${
                row.meeting_link
                  ? `<a href="${escapeHtml(row.meeting_link)}" target="_blank" class="join-btn">
                       <i class="bi bi-camera-video"></i> Join
                     </a>`
                  : '<span class="history-muted">N/A</span>'
              }
            </td>
            <td>
              <div class="history-message-box" title="${escapeHtml(row.consultant_message || '-')}">
                ${escapeHtml(row.consultant_message || '-')}
              </div>
            </td>
          </tr>
        `;
      });

      tableHTML += `
              </tbody>
            </table>
          </div>
        </div>
      `;

      container.innerHTML = tableHTML;
    })
    .catch(err => {
      console.error("Error fetching completed appointments:", err);
      document.getElementById("history").innerHTML =
        `<div class="alert alert-danger rounded-4 shadow-sm border-0">
           <strong>Failed to fetch completed appointments.</strong><br>
           <small>${escapeHtml(err.message)}</small>
         </div>`;
    });
}

// ===================== Consultant Profile =====================
function fetchConsultantProfile() {
  console.log("fetchConsultantProfile() called");

  fetch('/api/consultant/profile', { credentials: 'same-origin' })
    .then(response => {
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      return response.json().catch(() => { throw new Error('Invalid JSON from fetch_consultant_profile.php'); });
    })
    .then(data => {
      console.log("Profile Data:", data);

      const container = document.getElementById("profile_card_container");

      if (!container) {
        console.error("❌ profile_card_container not found");
        return;
      }
      if (data.error) {
        container.innerHTML = `<div class="alert alert-danger">${escapeHtml(data.error)}</div>`;
        return;
      }

      container.innerHTML = `
        <style>
          /* ==============================
             CONSULTME PREMIUM PROFILE UI
          ============================== */

          .consultme-profile-wrap {
            animation: fadeUpProfile .45s ease both;
          }

          @keyframes fadeUpProfile {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0px); }
          }

          .consultme-profile-card {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(14px);
            border-radius: 22px;
            border: 1px solid rgba(15,23,42,0.10);
            box-shadow: 0 22px 70px rgba(15,23,42,0.12);
            overflow: hidden;
            position: relative;
            transition: 0.25s ease;
          }

          .consultme-profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 90px rgba(15,23,42,0.16);
            border-color: rgba(37,99,235,0.20);
          }

          .consultme-profile-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
              radial-gradient(circle at top left, rgba(37,99,235,0.16), transparent 55%),
              radial-gradient(circle at bottom right, rgba(15,23,42,0.12), transparent 65%);
            opacity: 0.85;
            pointer-events: none;
            z-index: 0;
          }

          .consultme-profile-card * {
            position: relative;
            z-index: 2;
          }

          .consultme-profile-header {
            padding: 20px 22px;
            background: linear-gradient(135deg,
              rgba(37,99,235,0.12) 0%,
              rgba(14,165,233,0.10) 40%,
              rgba(255,255,255,0.92) 100%
            );
            border-bottom: 1px solid rgba(15,23,42,0.08);
          }

          .consultme-profile-title {
            font-size: 1.2rem;
            font-weight: 950;
            color: #0f172a;
            letter-spacing: -0.4px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
          }

          .consultme-profile-title i {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg,#0d6efd,#2563eb,#0ea5e9);
            color: white;
            box-shadow: 0 16px 45px rgba(37,99,235,0.25);
            font-size: 16px;
          }

          .consultme-profile-subtitle {
            margin-top: 6px;
            color: #64748b;
            font-weight: 750;
            font-size: 0.92rem;
          }

          .consultme-avatar {
            width: 135px;
            height: 135px;
            object-fit: cover;
            border-radius: 50%;
            border: 6px solid rgba(255,255,255,0.95);
            box-shadow: 0 18px 55px rgba(15,23,42,0.18);
            transition: 0.25s ease;
          }

          .consultme-avatar:hover {
            transform: scale(1.05);
          }

          .consultme-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            font-weight: 900;
            font-size: 0.82rem;
            color: white;
            background: linear-gradient(90deg,#0d6efd,#2563eb,#0ea5e9);
            box-shadow: 0 16px 40px rgba(37,99,235,0.20);
            letter-spacing: 0.4px;
          }

          .consultme-meta-box {
            background: rgba(255,255,255,0.82);
            border: 1px solid rgba(15,23,42,0.10);
            border-radius: 18px;
            padding: 14px 16px;
            box-shadow: 0 14px 35px rgba(15,23,42,0.06);
            transition: 0.25s ease;
          }

          .consultme-meta-box:hover {
            transform: translateY(-3px);
            border-color: rgba(37,99,235,0.22);
            box-shadow: 0 22px 60px rgba(15,23,42,0.10);
          }

          .consultme-meta-label {
            font-size: 0.82rem;
            font-weight: 950;
            color: #0f172a;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
          }

          .consultme-meta-label i {
            color: #2563eb;
          }

          .consultme-meta-value {
            font-size: 0.92rem;
            font-weight: 750;
            color: #475569;
            line-height: 1.6;
          }

          .btn-consultme-edit {
            border-radius: 14px;
            padding: 0.7rem 1.2rem;
            font-weight: 950;
            border: none;
            color: white;
            background: linear-gradient(90deg,#0d6efd,#2563eb,#0ea5e9);
            box-shadow: 0 18px 45px rgba(37,99,235,0.25);
            transition: 0.22s ease;
          }

          .btn-consultme-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 24px 60px rgba(37,99,235,0.35);
          }

          .consultme-divider {
            height: 1px;
            width: 100%;
            background: linear-gradient(90deg, rgba(15,23,42,0.12), rgba(15,23,42,0.04));
            margin: 18px 0;
          }

          .public-preview-card {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(14px);
            border-radius: 22px;
            border: 1px solid rgba(15,23,42,0.10);
            box-shadow: 0 22px 70px rgba(15,23,42,0.10);
            overflow: hidden;
            position: relative;
          }

          .public-preview-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
              radial-gradient(circle at top left, rgba(34,197,94,0.10), transparent 60%),
              radial-gradient(circle at bottom right, rgba(37,99,235,0.14), transparent 65%);
            opacity: 0.9;
            pointer-events: none;
            z-index: 0;
          }

          .public-preview-card * {
            position: relative;
            z-index: 2;
          }

          .preview-title {
            font-size: 1.05rem;
            font-weight: 950;
            color: #0f172a;
            letter-spacing: -0.3px;
          }

          .preview-note {
            font-weight: 750;
            color: #64748b;
            font-size: 0.92rem;
          }

          @media(max-width: 768px) {
            .consultme-avatar {
              width: 115px;
              height: 115px;
            }
          }
        </style>

        <div class="consultme-profile-wrap">

          <!-- Internal Profile Card -->
          <div class="consultme-profile-card mb-4">

            <div class="consultme-profile-header">
              <div class="consultme-profile-title">
                <i class="bi bi-person-badge"></i>
                My Consultant Profile
              </div>
              <div class="consultme-profile-subtitle">
                Manage your personal details & professional profile visibility.
              </div>
            </div>

            <div class="p-4">
              <div class="row align-items-center g-4">

                <!-- Profile Image -->
                <div class="col-md-3 text-center">
                  <img src="/uploads/consultants/${escapeHtml(data.profile_img || 'default.png')}"
                       class="consultme-avatar"
                       alt="Profile">

                  <div class="mt-3">
                    <span class="consultme-pill">
                      <i class="bi bi-award-fill"></i>
                      ${escapeHtml(data.area_of_expertise || 'No Expertise')}
                    </span>
                  </div>
                </div>

                <!-- Name + Contact -->
                <div class="col-md-5">
                  <h4 class="fw-bold mb-1" style="color:#0f172a; font-weight:950;">
                    ${escapeHtml(data.name || '')}
                  </h4>

                  <div class="text-muted fw-semibold small mb-2">
                    @${escapeHtml(data.username || '')}
                  </div>

                  <div class="consultme-meta-box mb-3">
                    <div class="consultme-meta-label">
                      <i class="bi bi-envelope-fill"></i> Email
                    </div>
                    <div class="consultme-meta-value">${escapeHtml(data.email || '')}</div>
                  </div>

                  <div class="consultme-meta-box mb-3">
                    <div class="consultme-meta-label">
                      <i class="bi bi-telephone-fill"></i> Phone
                    </div>
                    <div class="consultme-meta-value">+91 ${escapeHtml(data.phone || '')}</div>
                  </div>

                  <div class="consultme-meta-box">
                    <div class="consultme-meta-label">
                      <i class="bi bi-geo-alt-fill"></i> Location
                    </div>
                    <div class="consultme-meta-value">${escapeHtml(data.state || 'Not specified')}</div>
                  </div>
                </div>

                <!-- Rate + Experience -->
                <div class="col-md-4">
                  <div class="consultme-meta-box mb-3">
                    <div class="consultme-meta-label">
                      <i class="bi bi-currency-rupee"></i> Hourly Rate
                    </div>
                    <div class="consultme-meta-value">
                      Rs. ${escapeHtml(data.hourly_rate || 'N/A')} / Hour
                    </div>
                  </div>

                  <div class="consultme-meta-box mb-3">
                    <div class="consultme-meta-label">
                      <i class="bi bi-briefcase-fill"></i> Experience
                    </div>
                    <div class="consultme-meta-value">
                      ${escapeHtml(data.experience || 'Not mentioned')}
                    </div>
                  </div>

                  <div class="consultme-meta-box mb-3">
                    <div class="consultme-meta-label">
                      <i class="bi bi-person-workspace"></i> Profession
                    </div>
                    <div class="consultme-meta-value">
                      ${escapeHtml(data.identity || 'Consultant')}
                    </div>
                  </div>

                  <div class="consultme-meta-box">
                    <div class="consultme-meta-label">
                      <i class="bi bi-card-text"></i> Bio
                    </div>
                    <div class="consultme-meta-value">
                      ${escapeHtml(data.bio || 'Professional Consultant')}
                    </div>
                  </div>

                  <div class="text-end mt-4">
                    <button class="btn-consultme-edit"
                            onclick='openEditProfileModal(${JSON.stringify(data).replace(/"/g, "&quot;")})'>
                      <i class="bi bi-pencil-square me-2"></i>Edit Profile
                    </button>
                  </div>
                </div>

              </div>
            </div>
          </div>


          <!-- Public Profile Preview -->
          <div class="mt-4">
            <div class="mb-3">
              <div class="preview-title">
                <i class="bi bi-eye-fill text-primary me-2"></i>Public Profile Preview
              </div>
              <div class="preview-note">
                This is how your profile appears to users on ConsultME.
              </div>
            </div>

            <div class="public-preview-card p-4">
              <div class="text-center mb-4">
                <img src="/uploads/consultants/${escapeHtml(data.profile_img || 'default.png')}"
                     class="rounded-circle shadow"
                     style="
                       width: 110px;
                       height: 110px;
                       object-fit: cover;
                       border: 5px solid rgba(255,255,255,0.95);
                       box-shadow: 0 18px 50px rgba(15,23,42,0.18);
                     ">

                <h5 class="mt-3 mb-1 fw-bold" style="color:#0f172a;">
                  ${escapeHtml(data.name || '')}
                  <span class="text-muted fw-semibold" style="font-size:0.95rem;">
                    @${escapeHtml(data.username || '')}
                  </span>
                </h5>

                <div class="mt-2">
                  <span class="badge rounded-pill px-3 py-2"
                        style="
                          background: rgba(15,23,42,0.05);
                          border: 1px solid rgba(15,23,42,0.10);
                          font-weight: 900;
                          color:#0f172a;
                        ">
                    <i class="bi bi-star-fill text-warning me-1"></i>
                    ${
                      data.avg_rating && Number(data.avg_rating) > 0
                        ? `${escapeHtml(data.avg_rating)} / 5`
                        : 'No ratings yet'
                    }
                    (${escapeHtml(data.total_reviews || 0)})
                  </span>
                </div>

                <div class="d-flex align-items-center justify-content-center text-secondary small mt-2 fw-semibold">
                  <i class="bi bi-geo-alt-fill me-1 text-primary"></i>
                  ${escapeHtml(data.state || 'Not specified')}
                </div>
              </div>

              <div class="row g-3 small">

                <div class="col-md-6">
                  <div class="consultme-meta-box">
                    <div class="consultme-meta-label"><i class="bi bi-person-badge"></i> Profession</div>
                    <div class="consultme-meta-value">${escapeHtml(data.identity || '-')}</div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="consultme-meta-box">
                    <div class="consultme-meta-label"><i class="bi bi-award"></i> Specialist</div>
                    <div class="consultme-meta-value">${escapeHtml(data.area_of_expertise || '-')}</div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="consultme-meta-box">
                    <div class="consultme-meta-label"><i class="bi bi-currency-rupee"></i> Rate</div>
                    <div class="consultme-meta-value">
                      Rs ${escapeHtml(data.hourly_rate || 'N/A')} <span class="text-muted">/Hour</span>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="consultme-meta-box">
                    <div class="consultme-meta-label"><i class="bi bi-briefcase"></i> Experience</div>
                    <div class="consultme-meta-value">${escapeHtml(data.experience || '-')}</div>
                  </div>
                </div>

                <div class="col-12">
                  <div class="consultme-meta-box">
                    <div class="consultme-meta-label"><i class="bi bi-card-text"></i> Bio</div>
                    <div class="consultme-meta-value">${escapeHtml(data.bio || '-')}</div>
                  </div>
                </div>

              </div>

              <div class="d-grid mt-4">
                <button type="button"
                        class="btn rounded-pill fw-bold py-2"
                        style="
                          background: rgba(15,23,42,0.06);
                          border: 1px solid rgba(15,23,42,0.16);
                          color: #0f172a;
                        "
                        disabled>
                  <i class="bi bi-calendar-check me-2"></i>
                  Book Appointment (Visible to users only)
                </button>
              </div>

            </div>
          </div>

        </div>
      `;
    })
    .catch(err => {
      console.error("Profile fetch error:", err);
      const container = document.getElementById("profile_card_container");
      if (container) {
        container.innerHTML = `
          <div class="alert alert-danger rounded-4 shadow-sm border-0">
            Failed to load profile.<br>
            <small>${escapeHtml(err.message)}</small>
          </div>`;
      }
    });
}

function openEditProfileModal(data) {
  const setIf = (id, val) => {
    const el = document.getElementById(id);
    if (el) el.value = val ?? '';
    else console.warn(`openEditProfileModal: element #${id} not found`);
  };

  setIf("profile-name", data.name);
  setIf("profile-phone", data.phone);
  setIf("profile-expertise", data.area_of_expertise);
  setIf("profile-bio", data.bio);
  setIf("profile-experience", data.experience);
  setIf("profile-state", data.state);
  setIf("profile-identity", data.identity);
  setIf("profile-rate", data.hourly_rate);

  const modalEl = document.getElementById("editProfileModal");
  if (modalEl) {
    new bootstrap.Modal(modalEl).show();
  } else {
    console.error("openEditProfileModal: editProfileModal not found");
  }
}

function saveProfile() {
  const formData = new FormData();

  const safeAppend = (fieldName, elementId, isFile = false) => {
    const el = document.getElementById(elementId);
    if (!el) {
      console.warn(`saveProfile: element #${elementId} not found`);
      formData.append(fieldName, '');
      return;
    }

    if (isFile) {
      if (el.files && el.files.length > 0) {
        formData.append(fieldName, el.files[0]);
      } else {
        formData.append(fieldName, '');
      }
    } else {
      formData.append(fieldName, el.value ?? '');
    }
  };

  // Append fields (same as before)
  safeAppend("name", "profile-name");
  safeAppend("phone", "profile-phone");
  safeAppend("area_of_expertise", "profile-expertise");
  safeAppend("bio", "profile-bio");
  safeAppend("experience", "profile-experience");
  safeAppend("state", "profile-state");
  safeAppend("rate", "profile-rate");
  safeAppend("identity", "profile-identity");
  safeAppend("profile_image", "profile-image", true);

  // Progress UI (optional - safe)
  const container = document.getElementById("uploadProgressContainer");
  const bar = document.getElementById("uploadProgressBar");
  const text = document.getElementById("uploadProgressText");
  const saveBtn = document.querySelector("#editProfileModal .btn-primary");

  if (container) container.style.display = "block";
  if (bar) bar.style.width = "0%";
  if (text) text.innerText = "Uploading... 0%";
  if (saveBtn) saveBtn.disabled = true;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/api/consultant/profile", true);

  // ✅ Upload progress
  xhr.upload.onprogress = function (e) {
    if (e.lengthComputable) {
      const percent = Math.round((e.loaded / e.total) * 100);

      if (bar) bar.style.width = percent + "%";
      if (text) text.innerText = "Uploading... " + percent + "%";
    }
  };

  // ✅ Success (same logic as fetch)
  xhr.onload = function () {
    if (container) container.style.display = "none";
    if (saveBtn) saveBtn.disabled = false;

    let response;

    try {
      response = JSON.parse(xhr.responseText);
    } catch {
      response = { success: false, error: "Invalid JSON response" };
    }

    if (response.success) {
      alert("Profile updated successfully!");

      const modalEl = document.getElementById("editProfileModal");
      if (modalEl) {
        const modalInstance =
          bootstrap.Modal.getInstance(modalEl) ||
          new bootstrap.Modal(modalEl);
        modalInstance.hide();
      }

      fetchConsultantProfile();
    } else {
      alert("Update failed: " + (response.error || response.message || "Unknown error"));
      console.warn("update_consultant_profile response:", response);
    }
  };

  // ❌ Error (same as catch)
  xhr.onerror = function () {
    if (container) container.style.display = "none";
    if (saveBtn) saveBtn.disabled = false;

    console.error("Save profile error");
    alert("An error occurred while saving the profile.");
  };

  xhr.send(formData);
}

// ===================== Consultant Projects =====================
// Modern Browse Projects + Modal View & Apply
function showConsultantProjects(searchText = '', page = 1) {
  const container = document.getElementById('consultantProjectsContainer');
  const pagination = document.getElementById('consultantProjectsPagination');
  if (!container) {
    console.warn('consultantProjectsContainer not found');
    return;
  }

  container.innerHTML = '<p class="text-muted">Loading projects…</p>';
  if (pagination) pagination.innerHTML = '';

  const params = new URLSearchParams();
  params.set('page', String(page));
  params.set('per_page', '10');
  if (searchText) params.set('q', searchText);

  fetch('/api/consultant/projects?' + params.toString(), {
    credentials: 'same-origin'
  })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json().catch(async () => {
        const txt = await res.text().catch(() => '(no body)');
        throw new Error('Invalid JSON response: ' + txt);
      });
    })
    .then(json => {
      if (!json.success) {
        container.innerHTML = `<div class="alert alert-warning">Error: ${escapeHtml(json.message || 'Failed to load projects')}</div>`;
        return;
      }

      consultantProjectsById = {};
      (json.projects || []).forEach(p => {
        if (!p) return;
        const pid = String(p.project_id || p.id || '');
        if (pid) consultantProjectsById[pid] = p;
      });

      renderConsultantProjectCards(json.projects || []);
      renderConsultantPagination(json.page || 1, json.per_page || 10, json.total || 0);
    })
    .catch(err => {
      console.error('[projects] Fetch error:', err);
      container.innerHTML = `<div class="alert alert-danger">Error loading projects. See console for details.</div>`;
    });
}

function renderConsultantProjectCards(projects) {
  const container = document.getElementById('consultantProjectsContainer');
  if (!container) return;

  if (!projects || projects.length === 0) {
    container.innerHTML = '<div class="alert alert-info">No projects found.</div>';
    return;
  }

  const html = projects.map(p => {
    const pid = escapeHtml(p.project_id || p.id || '');
    const posted = p.posted_at ? new Date(p.posted_at).toLocaleString() : '—';
    const fullDesc = p.description || p.short_description || '';
    const short = fullDesc.substring(0, 300);
    const poster = escapeHtml(p.poster_name || p.username || 'User');
    const budget = escapeHtml(p.budget || 'N/A');
    const billing = escapeHtml(p.billing_cycle || '');
    const links = (p.links || '').trim();

    let linksHtml = '';
    if (links) {
      linksHtml = `<p class="mb-2 small"><strong>Links:</strong> ${escapeHtml(links)}</p>`;
    }

    return `
      <div class="card mb-3 border-0 rounded-4 project-card-premium"
           style="
             background: rgba(255,255,255,0.92);
             backdrop-filter: blur(14px);
             border: 1px solid rgba(15,23,42,0.10);
             box-shadow: 0 20px 65px rgba(15,23,42,0.10);
             overflow: hidden;
             position: relative;
             transition: 0.25s ease;
           ">
    
        <!-- Premium Glow Background -->
        <div style="
          position:absolute;
          inset:0;
          background:
            radial-gradient(circle at top left, rgba(37,99,235,0.14), transparent 60%),
            radial-gradient(circle at bottom right, rgba(15,23,42,0.10), transparent 65%);
          opacity: 0.9;
          pointer-events:none;
          z-index:0;
        "></div>
    
        <div class="card-body p-4" style="position:relative; z-index:2;">
    
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h5 class="card-title mb-1 fw-bold"
                  style="color:#0f172a; font-weight:950; letter-spacing:-0.4px;">
                ${escapeHtml(p.title || 'Untitled project')}
              </h5>
    
              <div class="small text-muted fw-semibold d-flex align-items-center gap-2">
                <i class="bi bi-calendar2-week text-primary"></i>
                Posted: ${escapeHtml(posted)}
              </div>
            </div>
    
            <span class="badge rounded-pill px-3 py-2"
                  style="
                    background: linear-gradient(90deg,#0d6efd,#2563eb,#0ea5e9);
                    color: white;
                    font-weight: 900;
                    font-size: 0.78rem;
                    letter-spacing: 0.4px;
                    box-shadow: 0 14px 35px rgba(37,99,235,0.22);
                  ">
              <i class="bi bi-lightning-charge-fill me-1"></i> Active
            </span>
          </div>
    
          <div class="d-flex flex-wrap gap-2 mb-3">
    
            <span class="badge rounded-pill px-3 py-2"
                  style="
                    background: rgba(255,255,255,0.75);
                    border: 1px solid rgba(15,23,42,0.10);
                    color:#0f172a;
                    font-weight: 900;
                    box-shadow: 0 10px 25px rgba(15,23,42,0.06);
                  ">
              <i class="bi bi-person-fill text-primary me-1"></i>
              By: <strong>${poster}</strong>
            </span>
    
            <span class="badge rounded-pill px-3 py-2"
                  style="
                    background: rgba(255,255,255,0.75);
                    border: 1px solid rgba(15,23,42,0.10);
                    color:#0f172a;
                    font-weight: 900;
                    box-shadow: 0 10px 25px rgba(15,23,42,0.06);
                  ">
              <i class="bi bi-currency-rupee text-success me-1"></i>
              Budget: <strong>${budget}</strong>
              ${billing ? ` <span class="text-muted fw-semibold">(${billing})</span>` : ''}
            </span>
    
          </div>
    
          <p class="mb-3"
             style="
               color:#334155;
               font-weight: 750;
               line-height: 1.7;
               font-size: 0.95rem;
             ">
            ${escapeHtml(short)}${fullDesc.length > 300 ? '…' : ''}
          </p>
    
          ${linksHtml}
    
          <div class="d-flex gap-2 mt-3">
    
            <button type="button"
                    class="btn btn-sm rounded-pill px-4 py-2 fw-bold"
                    style="
                      border: 1px solid rgba(37,99,235,0.30);
                      background: rgba(255,255,255,0.70);
                      color: #2563eb;
                      transition: 0.2s ease;
                    "
                    onclick="openProjectModal('${pid}')">
              <i class="bi bi-eye-fill me-2"></i>View
            </button>
    
            <button type="button"
                    class="btn btn-sm rounded-pill px-4 py-2 fw-bold text-white"
                    style="
                      border: none;
                      background: linear-gradient(90deg,#16a34a,#22c55e);
                      box-shadow: 0 14px 35px rgba(34,197,94,0.22);
                      transition: 0.2s ease;
                    "
                    onclick="openApplyModal('${pid}')">
              <i class="bi bi-send-fill me-2"></i>Apply
            </button>
    
          </div>
    
        </div>
      </div>
    
      <script>
        document.querySelectorAll('.project-card-premium').forEach(card => {
          card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-6px)';
            card.style.boxShadow = '0 30px 85px rgba(15,23,42,0.14)';
            card.style.borderColor = 'rgba(37,99,235,0.22)';
          });
    
          card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0px)';
            card.style.boxShadow = '0 20px 65px rgba(15,23,42,0.10)';
            card.style.borderColor = 'rgba(15,23,42,0.10)';
          });
        });
      </script>
    `;

  }).join('');

  container.innerHTML = html;
}

function renderConsultantPagination(page, per_page, total) {
  const wrapper = document.getElementById('consultantProjectsPagination');
  if (!wrapper) return;

  const totalPages = Math.max(1, Math.ceil(total / per_page));
  const currentSearch = (document.getElementById('consultantProjectSearch')?.value || '').trim();

  let html = `<span class="small text-muted">Page ${page} of ${totalPages}</span>`;

  if (page > 1) {
    html += `
      <button class="btn btn-sm btn-outline-secondary ms-2 rounded-pill"
              onclick="showConsultantProjects('${currentSearch.replace(/'/g, "\\'")}', ${page - 1})">
        Prev
      </button>`;
  }
  if (page < totalPages) {
    html += `
      <button class="btn btn-sm btn-outline-secondary ms-2 rounded-pill"
              onclick="showConsultantProjects('${currentSearch.replace(/'/g, "\\'")}', ${page + 1})">
        Next
      </button>`;
  }

  wrapper.innerHTML = html;
}

// ---------- Project Details + Apply Modal ----------
function ensureProjectModalExists() {
  if (projectModalInitialized) return;
  projectModalInitialized = true;

    const modalHtml = `
          <div class="modal fade" id="projectDetailsModal" tabindex="-1"
               aria-labelledby="projectDetailsLabel" aria-hidden="true">
        
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
              <div class="modal-content rounded-4 border-0 overflow-hidden"
                   style="
                     background: rgba(255,255,255,0.94);
                     backdrop-filter: blur(14px);
                     box-shadow: 0 28px 90px rgba(15,23,42,0.20);
                     position: relative;
                   ">
        
                <!-- Premium Background Glow -->
                <div style="
                  position:absolute;
                  inset:0;
                  background:
                    radial-gradient(circle at top left, rgba(37,99,235,0.14), transparent 60%),
                    radial-gradient(circle at bottom right, rgba(15,23,42,0.12), transparent 65%);
                  opacity: 0.9;
                  pointer-events:none;
                  z-index:0;
                "></div>
        
                <!-- HEADER -->
                <div class="modal-header border-0 px-4 py-4"
                     style="
                       position:relative;
                       z-index:2;
                       background: linear-gradient(135deg,
                         rgba(37,99,235,0.14) 0%,
                         rgba(14,165,233,0.10) 45%,
                         rgba(255,255,255,0.94) 100%
                       );
                       border-bottom: 1px solid rgba(15,23,42,0.08);
                     ">
        
                  <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center"
                         style="
                           width: 48px;
                           height: 48px;
                           border-radius: 16px;
                           background: linear-gradient(135deg,#0d6efd,#2563eb,#0ea5e9);
                           box-shadow: 0 18px 45px rgba(37,99,235,0.25);
                           color: white;
                           font-size: 18px;
                         ">
                      <i class="bi bi-folder2-open"></i>
                    </div>
        
                    <div>
                      <h5 class="modal-title fw-bold mb-0"
                          id="projectDetailsLabel"
                          style="color:#0f172a; letter-spacing:-0.4px;">
                        Project Details
                      </h5>
                      <small class="text-muted fw-semibold">
                        View project information & apply professionally
                      </small>
                    </div>
                  </div>
        
                  <button type="button" class="btn-close" data-bs-dismiss="modal"
                          aria-label="Close"></button>
                </div>
        
                <!-- BODY -->
                <div class="modal-body px-4 pt-4 pb-3"
                     style="position:relative; z-index:2;">
        
                  <!-- Project Details View -->
                  <div id="projectDetailsView"></div>
        
                  <!-- Apply View -->
                  <div id="projectApplyView" style="display:none;">
        
                    <div class="rounded-4 p-4 mt-3"
                         style="
                           background: rgba(255,255,255,0.78);
                           border: 1px solid rgba(15,23,42,0.10);
                           box-shadow: 0 18px 55px rgba(15,23,42,0.08);
                         ">
        
                      <div class="mb-3">
                        <h6 class="fw-bold mb-1" style="color:#0f172a;">
                          <i class="bi bi-send-fill text-primary me-2"></i>
                          Apply to this Project
                        </h6>
                        <p class="text-muted fw-semibold small mb-0">
                          Write a short professional message and share your portfolio.
                        </p>
                      </div>
        
                      <form id="projectApplyForm" onsubmit="return false;">
                        <input type="hidden" id="apply_project_id">
        
                        <div class="mb-3">
                          <label class="form-label fw-bold small"
                                 style="color:#0f172a;">
                            Message to project owner <span class="text-danger">*</span>
                          </label>
        
                          <textarea id="apply_user_msg"
                                    class="form-control rounded-4"
                                    rows="3"
                                    maxlength="150"
                                    placeholder="Briefly explain why you are a good fit (max 150 characters)"
                                    required
                                    style="
                                      padding: 0.95rem 1rem;
                                      font-weight: 700;
                                      border: 1px solid rgba(15,23,42,0.14);
                                      background: rgba(255,255,255,0.95);
                                      resize: none;
                                    "></textarea>
        
                          <div class="small text-muted mt-1 fw-semibold">
                            <i class="bi bi-info-circle me-1"></i>
                            Max 150 characters.
                          </div>
                        </div>
        
                        <div class="mb-0">
                          <label class="form-label fw-bold small"
                                 style="color:#0f172a;">
                            Portfolio / Work link
                          </label>
        
                          <input type="url"
                                 id="apply_portfolio_link"
                                 class="form-control rounded-4"
                                 maxlength="100"
                                 placeholder="https://your-portfolio-or-github.com"
                                 style="
                                   padding: 0.95rem 1rem;
                                   font-weight: 700;
                                   border: 1px solid rgba(15,23,42,0.14);
                                   background: rgba(255,255,255,0.95);
                                 ">
                        </div>
                      </form>
        
                    </div>
                  </div>
                </div>
        
                <!-- FOOTER -->
                <div class="modal-footer border-0 px-4 pb-4 pt-2 d-flex gap-2 flex-wrap"
                     style="position:relative; z-index:2;">
        
                  <button type="button"
                          id="projectDetailsApplyBtn"
                          class="btn rounded-pill px-4 py-2 fw-bold text-white"
                          style="
                            background: linear-gradient(90deg,#16a34a,#22c55e);
                            border: none;
                            box-shadow: 0 16px 40px rgba(34,197,94,0.22);
                            transition: 0.2s ease;
                          ">
                    <i class="bi bi-send-fill me-2"></i>Apply
                  </button>
        
                  <button type="button"
                          id="projectApplyBackBtn"
                          class="btn rounded-pill px-4 py-2 fw-bold"
                          style="
                            display:none;
                            background: rgba(15,23,42,0.06);
                            border: 1px solid rgba(15,23,42,0.14);
                            color: #0f172a;
                            transition: 0.2s ease;
                          ">
                    <i class="bi bi-arrow-left-circle me-2"></i>Back
                  </button>
        
                  <button type="button"
                          id="projectApplySubmitBtn"
                          class="btn rounded-pill px-4 py-2 fw-bold text-white"
                          style="
                            display:none;
                            background: linear-gradient(90deg,#0d6efd,#2563eb,#0ea5e9);
                            border: none;
                            box-shadow: 0 18px 45px rgba(37,99,235,0.25);
                            transition: 0.2s ease;
                          ">
                    <i class="bi bi-check-circle-fill me-2"></i>Submit Application
                  </button>
        
                  <button type="button"
                          class="btn rounded-pill px-4 py-2 fw-bold"
                          data-bs-dismiss="modal"
                          style="
                            background: rgba(15,23,42,0.06);
                            border: 1px solid rgba(15,23,42,0.16);
                            color: #0f172a;
                            transition: 0.2s ease;
                          ">
                    <i class="bi bi-x-circle me-2"></i>Close
                  </button>
        
                </div>
        
              </div>
            </div>
          </div>
    `;

  document.body.insertAdjacentHTML('beforeend', modalHtml);

  const applyBtn = document.getElementById('projectDetailsApplyBtn');
  if (applyBtn) applyBtn.addEventListener('click', () => enterApplyMode());

  const backBtn = document.getElementById('projectApplyBackBtn');
  if (backBtn) backBtn.addEventListener('click', () => exitApplyMode());

  const submitBtn = document.getElementById('projectApplySubmitBtn');
  if (submitBtn) submitBtn.addEventListener('click', () => submitProjectApplication());
}

function openProjectModal(projectId) {
  ensureProjectModalExists();
  currentProjectId = String(projectId);

  const p = consultantProjectsById[currentProjectId];
  if (!p) {
    console.warn('Project not found in cache, falling back to direct view page');
    window.location.href = '/projects/view.php?id=' + encodeURIComponent(projectId);
    return;
  }

  exitApplyMode(true);

  const posted = p.posted_at ? new Date(p.posted_at).toLocaleString() : '—';
  const poster = escapeHtml(p.poster_name || p.username || 'User');
  const budget = escapeHtml(p.budget || 'N/A');
  const billing = escapeHtml(p.billing_cycle || '');
  const links = (p.links || '').trim();
  const shortDesc = p.short_description || '';
  const desc = p.description || '';

  let linksHtml = '';
  if (links) {
    linksHtml = `
      <div class="mb-3">
        <h6 class="fw-semibold">Reference Links</h6>
        <p class="mb-0 small">${escapeHtml(links)}</p>
      </div>`;
  }

    const detailsHtml = `
          <div class="mb-3"
               style="
                 background: rgba(255,255,255,0.85);
                 border: 1px solid rgba(15,23,42,0.10);
                 border-radius: 18px;
                 padding: 18px 18px;
                 box-shadow: 0 18px 50px rgba(15,23,42,0.08);
               ">
        
            <h5 class="mb-1"
                style="
                  font-weight: 950;
                  color: #0f172a;
                  letter-spacing: -0.4px;
                ">
              ${escapeHtml(p.title || 'Untitled project')}
            </h5>
        
            <p class="mb-1 text-muted small"
               style="font-weight: 700; display:flex; align-items:center; gap:8px;">
              <i class="bi bi-person-badge text-primary"></i>
              Posted by <strong style="color:#0f172a;">${poster}</strong>
              <span style="opacity:0.6;">•</span>
              <i class="bi bi-calendar-event text-primary"></i>
              ${escapeHtml(posted)}
            </p>
        
            <p class="mb-2 text-muted small"
               style="font-weight: 700; display:flex; align-items:center; gap:8px;">
              <i class="bi bi-currency-rupee text-success"></i>
              Budget:
              <strong style="color:#0f172a;">${budget}</strong>
              ${billing ? `
                <span class="badge rounded-pill px-3 py-2"
                      style="
                        background: rgba(15,23,42,0.06);
                        border: 1px solid rgba(15,23,42,0.10);
                        font-weight: 900;
                        color: #0f172a;
                      ">
                  ${billing}
                </span>` : ''}
            </p>
          </div>
        
          ${shortDesc ? `
            <div class="mb-3"
                 style="
                   background: rgba(255,255,255,0.80);
                   border: 1px solid rgba(15,23,42,0.08);
                   border-radius: 18px;
                   padding: 16px 18px;
                   box-shadow: 0 14px 40px rgba(15,23,42,0.06);
                 ">
              <h6 class="fw-semibold mb-2"
                  style="color:#0f172a; font-weight:950;">
                <i class="bi bi-lightbulb-fill text-warning me-2"></i>
                Summary
              </h6>
        
              <p class="mb-0"
                 style="
                   color:#334155;
                   font-weight: 750;
                   line-height: 1.7;
                 ">
                ${escapeHtml(shortDesc)}
              </p>
            </div>` : ''}
        
          <div class="mb-3"
               style="
                 background: rgba(255,255,255,0.80);
                 border: 1px solid rgba(15,23,42,0.08);
                 border-radius: 18px;
                 padding: 16px 18px;
                 box-shadow: 0 14px 40px rgba(15,23,42,0.06);
               ">
        
            <h6 class="fw-semibold mb-2"
                style="color:#0f172a; font-weight:950;">
              <i class="bi bi-file-earmark-text-fill text-primary me-2"></i>
              Full Description
            </h6>
        
            <p class="mb-0"
               style="
                 color:#334155;
                 font-weight: 750;
                 line-height: 1.7;
               ">
              ${escapeHtml(desc || 'No description provided.')}
            </p>
          </div>
        
          ${linksHtml}
    `;


  const viewDiv = document.getElementById('projectDetailsView');
  if (viewDiv) viewDiv.innerHTML = detailsHtml;

  const titleEl = document.getElementById('projectDetailsLabel');
  if (titleEl) titleEl.textContent = p.title || 'Project Details';

  const modalEl = document.getElementById('projectDetailsModal');
  const modal = new bootstrap.Modal(modalEl);
  modal.show();
}

function enterApplyMode() {
  ensureProjectModalExists();
  if (!currentProjectId) return;

  const hiddenId = document.getElementById('apply_project_id');
  if (hiddenId) hiddenId.value = currentProjectId;

  const msgEl = document.getElementById('apply_user_msg');
  const linkEl = document.getElementById('apply_portfolio_link');
  if (msgEl) msgEl.value = '';
  if (linkEl) linkEl.value = '';

  const detailsView = document.getElementById('projectDetailsView');
  const applyView = document.getElementById('projectApplyView');
  if (detailsView) detailsView.style.display = 'none';
  if (applyView) applyView.style.display = 'block';

  const detailsApplyBtn = document.getElementById('projectDetailsApplyBtn');
  const backBtn = document.getElementById('projectApplyBackBtn');
  const submitBtn = document.getElementById('projectApplySubmitBtn');

  if (detailsApplyBtn) detailsApplyBtn.style.display = 'none';
  if (backBtn) backBtn.style.display = 'inline-block';
  if (submitBtn) submitBtn.style.display = 'inline-block';
}

function exitApplyMode(skipReset) {
  ensureProjectModalExists();

  const detailsView = document.getElementById('projectDetailsView');
  const applyView = document.getElementById('projectApplyView');
  if (detailsView) detailsView.style.display = 'block';
  if (applyView) applyView.style.display = 'none';

  const detailsApplyBtn = document.getElementById('projectDetailsApplyBtn');
  const backBtn = document.getElementById('projectApplyBackBtn');
  const submitBtn = document.getElementById('projectApplySubmitBtn');

  if (detailsApplyBtn) detailsApplyBtn.style.display = 'inline-block';
  if (backBtn) backBtn.style.display = 'none';
  if (submitBtn) submitBtn.style.display = 'none';

  if (!skipReset) {
    const form = document.getElementById('projectApplyForm');
    if (form) form.reset();
  }
}

function submitProjectApplication() {
  const projectId = document.getElementById('apply_project_id')?.value?.trim();
  const msgEl = document.getElementById('apply_user_msg');
  const linkEl = document.getElementById('apply_portfolio_link');

  if (!projectId) {
    alert('Missing project id.');
    return;
  }

  const user_msg = (msgEl?.value || '').trim();
  const portfolio_link = (linkEl?.value || '').trim();

  if (!user_msg) {
    alert('Please enter a short message to the project owner.');
    msgEl?.focus();
    return;
  }
  if (user_msg.length > 150) {
    alert('Message must be at most 150 characters.');
    msgEl?.focus();
    return;
  }
  if (portfolio_link.length > 100) {
    alert('Portfolio link must be at most 100 characters.');
    linkEl?.focus();
    return;
  }

  const payload = {
    project_id: projectId,
    user_msg,
    portfolio_link
  };

  fetch('/api/projects/apply', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
    credentials: 'same-origin'
  })
    .then(res => res.json().catch(() => ({ success: false, error: 'Invalid JSON response' })))
    .then(json => {
      if (!json.success) {
        alert('Failed to submit application: ' + (json.error || json.message || 'Unknown error'));
        return;
      }

      alert(json.message || 'Application submitted successfully!');
      const modalEl = document.getElementById('projectDetailsModal');
      const modal = bootstrap.Modal.getInstance(modalEl);
      if (modal) modal.hide();
    })
    .catch(err => {
      console.error('Application submit error:', err);
      alert('An error occurred while submitting your application.');
    });
}

function openApplyModal(projectId) {
  openProjectModal(projectId);
  setTimeout(() => enterApplyMode(), 150);
}

// ===================== DOM Ready =====================
document.addEventListener('DOMContentLoaded', () => {
  // Default tab
  showSection('appointments');
});