/* ---------------------------
  main.js (updated)
  - Integrates applicant View Profile popup
---------------------------- */

function showSection(id) {
  console.log('[projects] showSection ->', id);
  const sections = ['consultants', 'categories', 'appointments', 'history', 'profile', 'projects'];
  sections.forEach(sec => {
    const el = document.getElementById(sec);
    if (el) el.style.display = (sec === id) ? 'block' : 'none';
  });

  const pageTitle = document.getElementById("page-title");
  if (pageTitle) pageTitle.textContent = id.charAt(0).toUpperCase() + id.slice(1);

  document.querySelectorAll(".sidebar a").forEach(link => {
    if (link.classList) link.classList.remove("active");
  });
  const link = document.querySelector(`.sidebar a[href="#${id}"]`);
  if (link && link.classList) link.classList.add("active");

  // Call loaders if present (guarded)
  //if (id === 'consultants' && typeof fetchConsultants === 'function') fetchConsultants();
  if (id === 'categories' && typeof fetchCategories === 'function') fetchCategories();
  if (id === 'profile' && typeof fetchUserDetails === 'function') fetchUserDetails();
  if (id === 'appointments' && typeof fetchAppointments === 'function') fetchAppointments();
  if (id === 'history' && typeof fetchHistory === 'function') fetchHistory();

  // Projects loader (guarded)
  if (id === 'projects') {
    if (typeof loadProjects === 'function') {
      try {
        loadProjects('active', 1);
      } catch (e) {
        console.error('[projects] loadProjects threw:', e);
        const container = document.getElementById('projectsContainer');
        if (container) container.innerHTML = `<p class="text-danger">Error loading projects. See console.</p>`;
      }
    } else {
      console.warn('[projects] loadProjects() is not defined');
      const container = document.getElementById('projectsContainer');
      if (container) container.innerHTML = `<p class="text-warning">Projects loader not available.</p>`;
    }
  }
}

function openForm() {
  document.getElementById("popupForm").style.display = "block";
}

function closeForm() {
  document.getElementById("popupForm").style.display = "none";
}

function fetchConsultants() {
  fetch('/api/consultants')
    .then(response => response.json())
    .then(data => {
      const container = document.getElementById('consultants');
      container.innerHTML = '<p>List of Available consultants.</p>'; // reset

      if (Array.isArray(data) && data.length > 0) {
        const row = document.createElement('div');
        row.className = 'row';

        data.forEach((consultant, index) => {
          const col = document.createElement('div');
          col.className = 'col-md-6 mb-4';

          col.innerHTML = `
                <style>
                  /* ===============================
                     CONSULTME PREMIUM CONSULTANT CARD
                  =============================== */
                
                  .consultant-card {
                    border-radius: 22px;
                    border: 1px solid rgba(15, 23, 42, 0.10);
                    background: rgba(255, 255, 255, 0.85);
                    backdrop-filter: blur(14px);
                    overflow: hidden;
                    transition: 0.28s ease;
                    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
                    position: relative;
                  }
                
                  /* Premium hover glow */
                  .consultant-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 28px 65px rgba(15, 23, 42, 0.14);
                    border-color: rgba(37, 99, 235, 0.25);
                  }
                
                  .consultant-card::before {
                    content: "";
                    position: absolute;
                    inset: 0;
                    background: radial-gradient(circle at top left, rgba(37, 99, 235, 0.18), transparent 55%),
                                radial-gradient(circle at bottom right, rgba(15, 23, 42, 0.10), transparent 60%);
                    opacity: 0.8;
                    pointer-events: none;
                    z-index: 0;
                  }
                
                  .consultant-card * {
                    position: relative;
                    z-index: 2;
                  }
                
                  /* Header */
                  .card-header-modern {
                    background: linear-gradient(135deg,
                      rgba(15, 23, 42, 0.04) 0%,
                      rgba(37, 99, 235, 0.06) 45%,
                      rgba(255, 255, 255, 0.85) 100%
                    );
                    padding: 1.8rem 1.2rem 1.2rem 1.2rem;
                    border-bottom: 1px solid rgba(15, 23, 42, 0.08);
                    text-align: center;
                  }
                
                  /* Avatar */
                  .consultant-avatar {
                    width: 112px;
                    height: 112px;
                    border-radius: 50%;
                    object-fit: cover;
                    border: 5px solid rgba(255,255,255,0.95);
                    box-shadow: 0 14px 40px rgba(15, 23, 42, 0.18);
                    transition: 0.25s ease;
                  }
                
                  .consultant-card:hover .consultant-avatar {
                    transform: scale(1.04);
                  }
                
                  /* Name + Username */
                  .consultant-name {
                    font-size: 1.15rem;
                    font-weight: 800;
                    color: #0f172a;
                    margin-top: 14px;
                    margin-bottom: 0px;
                    letter-spacing: -0.4px;
                  }
                
                  .consultant-username {
                    font-size: 0.9rem;
                    font-weight: 600;
                    color: #64748b;
                    margin-top: 2px;
                  }
                
                  /* Meta Row */
                  .meta-row {
                    display: flex;
                    justify-content: center;
                    gap: 0.7rem;
                    margin-top: 1.1rem;
                    flex-wrap: wrap;
                  }
                
                  /* Premium Pills */
                  .meta-pill {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.45rem;
                    font-size: 0.86rem;
                    font-weight: 700;
                    padding: 0.45rem 0.85rem;
                    border-radius: 50px;
                    background: rgba(255,255,255,0.88);
                    border: 1px solid rgba(15, 23, 42, 0.10);
                    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
                    color: #0f172a;
                    white-space: nowrap;
                  }
                
                  .meta-pill small {
                    font-size: 0.78rem;
                    font-weight: 700;
                    color: #64748b;
                  }
                
                  .meta-pill.rating {
                    border-color: rgba(245, 158, 11, 0.35);
                    background: linear-gradient(135deg, rgba(245,158,11,0.14), rgba(255,255,255,0.85));
                  }
                
                  .meta-pill.location {
                    border-color: rgba(37, 99, 235, 0.25);
                    background: linear-gradient(135deg, rgba(37,99,235,0.12), rgba(255,255,255,0.85));
                  }
                
                  .meta-icon {
                    width: 16px;
                    height: 16px;
                    stroke-width: 2.2;
                  }
                
                  /* Body */
                  .consultant-body {
                    padding: 1.6rem 1.6rem 1.8rem 1.6rem;
                  }
                
                  .consultant-body p {
                    font-size: 0.93rem;
                    color: #334155;
                    margin-bottom: 0.65rem;
                    line-height: 1.65;
                  }
                
                  .consultant-body p strong {
                    color: #0f172a;
                    font-weight: 800;
                  }
                
                  /* Divider */
                  .divider {
                    height: 1px;
                    background: linear-gradient(90deg,
                      rgba(15, 23, 42, 0.12),
                      rgba(15, 23, 42, 0.05),
                      rgba(15, 23, 42, 0.02)
                    );
                    margin: 1rem 0;
                  }
                
                  /* Bio Clamp */
                  .bio-text {
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    text-overflow: ellipsis;
                  }
                
                  /* Buttons */
                  .btn-row {
                    margin-top: 1.25rem;
                    display: flex;
                    gap: 0.7rem;
                  }
                
                  .book-btn {
                    flex: 1;
                    font-weight: 800;
                    padding: 0.72rem;
                    border-radius: 14px;
                    border: none;
                    color: white;
                    background: linear-gradient(90deg, #0d6efd, #2563eb, #0ea5e9);
                    box-shadow: 0 18px 40px rgba(37, 99, 235, 0.25);
                    transition: 0.22s ease;
                  }
                
                  .book-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 24px 55px rgba(37, 99, 235, 0.35);
                  }
                
                  .view-profile-btn {
                    flex: 1;
                    font-weight: 800;
                    padding: 0.72rem;
                    border-radius: 14px;
                    border: 1px solid rgba(15, 23, 42, 0.22);
                    color: #0f172a;
                    background: rgba(255,255,255,0.75);
                    transition: 0.22s ease;
                  }
                
                  .view-profile-btn:hover {
                    background: rgba(15, 23, 42, 0.05);
                    border-color: rgba(37, 99, 235, 0.35);
                    color: #2563eb;
                    transform: translateY(-2px);
                    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
                  }
                
                  /* Responsive */
                  @media (max-width: 420px) {
                    .consultant-avatar {
                      width: 92px;
                      height: 92px;
                    }
                
                    .consultant-body {
                      padding: 1.25rem;
                    }
                
                    .btn-row {
                      flex-direction: column;
                    }
                  }
                </style>
                
                <div class="card consultant-card h-100 p-0">
                
                  <!-- HEADER -->
                  <div class="card-header-modern">
                
                    <img src="/uploads/consultants/${consultant.profile_img || 'default.png'}"
                         class="consultant-avatar"
                         alt="${consultant.name}" />
                
                    <div class="consultant-name">${consultant.name}</div>
                    <div class="consultant-username">@${consultant.username}</div>
                
                    <div class="meta-row">
                
                      <!-- Rating -->
                      <div class="meta-pill rating">
                        <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.48 3.5l2.1 4.45c.16.33.48.56.84.6l4.9.56-3.65 3.46c-.27.25-.38.63-.3.99l.98 4.78-4.32-2.35a1 1 0 0 0-.96 0l-4.32 2.35.98-4.78c.08-.36-.03-.74-.3-.99L3.64 9.11l4.9-.56c.36-.04.68-.27.84-.6l2.1-4.45a1 1 0 0 1 1.8 0z"/>
                        </svg>
                
                        ${consultant.avg_rating ? `${consultant.avg_rating} / 5` : 'No ratings'}
                        <small>(${consultant.total_reviews || 0})</small>
                      </div>
                
                      <!-- Location -->
                      <div class="meta-pill location">
                        <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21s7-5.25 7-10.5S16.14 3 12 3 5 6.5 5 10.5 12 21 12 21z"/>
                          <circle cx="12" cy="10.5" r="2.5"/>
                        </svg>
                
                        ${consultant.state || "Not specified"}
                      </div>
                
                    </div>
                
                  </div>
                
                  <!-- BODY -->
                  <div class="consultant-body">
                
                    <p><strong>Profession:</strong> ${consultant.identity || "Consultant"}</p>
                    <p><strong>Specialist:</strong> ${consultant.area_of_expertise || "-"}</p>
                
                    <p>
                      <strong>Rate:</strong>
                      <span class="fw-bold text-primary">₹${consultant.hourly_rate || "0"}</span>
                      <span class="text-muted">/Hour</span>
                    </p>
                
                    <div class="divider"></div>
                
                    <p><strong>Bio:</strong>
                      <span class="bio-text">${consultant.bio || "No bio available."}</span>
                    </p>
                
                    <p><strong>Experience:</strong> ${consultant.experience || "Not specified"}</p>
                
                    <div class="btn-row">
                      <button type="button"
                              class="btn book-btn"
                              data-index="${index}">
                        <i class="fa-solid fa-calendar-check me-2"></i>Book Appointment
                      </button>
                
                      <button type="button"
                              class="btn view-profile-btn"
                              data-username="${consultant.username}">
                        <i class="fa-solid fa-user me-2"></i>View Profile
                      </button>
                    </div>
                
                  </div>
                </div>
                `;


          row.appendChild(col);
        });

        container.appendChild(row);

        // Create modal HTML only once
        if (!document.getElementById('appointmentModal')) {
          const modal = document.createElement('div');
          modal.innerHTML = `
            <style>
              /* ===============================
                 CONSULTME PREMIUM MODAL DESIGN
              =============================== */
            
              #appointmentModal .modal-dialog {
                max-width: 520px;
              }
            
              #appointmentModal .modal-content {
                border-radius: 22px;
                border: 1px solid rgba(15, 23, 42, 0.12);
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(16px);
                box-shadow: 0 35px 90px rgba(15, 23, 42, 0.22);
                overflow: hidden;
              }
            
              /* Premium Header */
              #appointmentModal .modal-header {
                border: none;
                padding: 1.4rem 1.6rem;
                background: linear-gradient(135deg,
                  rgba(15, 23, 42, 0.04) 0%,
                  rgba(37, 99, 235, 0.12) 45%,
                  rgba(255, 255, 255, 0.9) 100%
                );
                border-bottom: 1px solid rgba(15, 23, 42, 0.08);
              }
            
              #appointmentModal .modal-title {
                font-weight: 900;
                font-size: 1.25rem;
                color: #0f172a;
                letter-spacing: -0.5px;
              }
            
              /* Body */
              #appointmentModal .modal-body {
                padding: 1.6rem;
              }
            
              /* Form Labels */
              #appointmentModal .form-label {
                font-weight: 800;
                color: #0f172a;
                font-size: 0.92rem;
                margin-bottom: 0.45rem;
              }
            
              /* Inputs */
              #appointmentModal .form-control,
              #appointmentModal .form-select {
                border-radius: 14px;
                padding: 0.8rem 1rem;
                border: 1px solid rgba(15, 23, 42, 0.14);
                background: rgba(255, 255, 255, 0.92);
                font-weight: 600;
                font-size: 0.95rem;
                color: #0f172a;
                transition: 0.2s ease;
              }
            
              #appointmentModal .form-control:focus,
              #appointmentModal .form-select:focus {
                border-color: rgba(37, 99, 235, 0.55);
                box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
              }
            
              #appointmentModal textarea.form-control {
                min-height: 110px;
                resize: none;
              }
            
              /* Fancy icon inside label row */
              .label-row {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 6px;
              }
            
              .label-icon {
                width: 34px;
                height: 34px;
                border-radius: 10px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, rgba(37,99,235,0.16), rgba(255,255,255,0.8));
                border: 1px solid rgba(37,99,235,0.22);
                color: #2563eb;
                font-size: 14px;
                box-shadow: 0 10px 22px rgba(15,23,42,0.06);
              }
            
              /* Footer */
              #appointmentModal .modal-footer {
                border: none;
                padding: 1.2rem 1.6rem 1.6rem 1.6rem;
                display: flex;
                gap: 0.75rem;
              }
            
              /* Buttons */
              .btn-consultme-primary {
                flex: 1;
                border-radius: 14px;
                padding: 0.8rem 1rem;
                font-weight: 900;
                border: none;
                color: white;
                background: linear-gradient(90deg, #0d6efd, #2563eb, #0ea5e9);
                box-shadow: 0 18px 40px rgba(37, 99, 235, 0.28);
                transition: 0.2s ease;
              }
            
              .btn-consultme-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 24px 55px rgba(37, 99, 235, 0.35);
              }
            
              .btn-consultme-outline {
                flex: 1;
                border-radius: 14px;
                padding: 0.8rem 1rem;
                font-weight: 900;
                border: 1px solid rgba(15, 23, 42, 0.22);
                background: rgba(255,255,255,0.85);
                color: #0f172a;
                transition: 0.2s ease;
              }
            
              .btn-consultme-outline:hover {
                transform: translateY(-2px);
                background: rgba(15, 23, 42, 0.04);
                border-color: rgba(37, 99, 235, 0.40);
                color: #2563eb;
                box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
              }
            
              /* Modal animation smoother */
              .modal.fade .modal-dialog {
                transform: translateY(20px) scale(0.98);
                transition: transform 0.25s ease;
              }
            
              .modal.show .modal-dialog {
                transform: translateY(0px) scale(1);
              }
            
              @media(max-width: 520px) {
                #appointmentModal .modal-dialog {
                  margin: 0.8rem;
                }
                #appointmentModal .modal-footer {
                  flex-direction: column;
                }
              }
            </style>
            
            <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
            
                  <!-- HEADER -->
                  <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">
                      <i class="fa-solid fa-calendar-check text-primary me-2"></i>
                      Book Appointment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
            
                  <!-- BODY -->
                  <div class="modal-body">
                    <form id="appointmentForm">
            
                      <!-- Date -->
                      <div class="mb-3">
                        <div class="label-row">
                          <span class="label-icon"><i class="fa-regular fa-calendar"></i></span>
                          <label for="appointmentDate" class="form-label mb-0">Select Date</label>
                        </div>
                        <input type="date" class="form-control" id="appointmentDate" required>
                      </div>
            
                      <!-- Time -->
                      <div class="mb-3">
                        <div class="label-row">
                          <span class="label-icon"><i class="fa-regular fa-clock"></i></span>
                          <label for="appointmentTime" class="form-label mb-0">Select Time</label>
                        </div>
                        <input type="time" class="form-control" id="appointmentTime" required>
                      </div>
            
                      <!-- Mode -->
                      <div class="mb-3">
                        <div class="label-row">
                          <span class="label-icon"><i class="fa-solid fa-video"></i></span>
                          <label for="appointmentMode" class="form-label mb-0">Appointment Mode</label>
                        </div>
                        <select class="form-select" id="appointmentMode" required>
                          <option value="">Choose Mode</option>
                          <option value="Online">Online (Google Meet / Zoom)</option>
                          <option value="Offline">Offline (In-person)</option>
                        </select>
                      </div>
            
                      <!-- Requests -->
                      <div class="mb-3">
                        <div class="label-row">
                          <span class="label-icon"><i class="fa-solid fa-pen-to-square"></i></span>
                          <label for="appointmentRequest" class="form-label mb-0">Special Request (Optional)</label>
                        </div>
                        <textarea class="form-control" id="appointmentRequest" rows="3"
                          placeholder="Write your requirement briefly..."></textarea>
                      </div>
            
                      <input type="hidden" id="selectedConsultant">
            
                      <!-- FOOTER -->
                      <div class="modal-footer p-0 pt-3">
                        <button type="button" class="btn btn-consultme-outline" data-bs-dismiss="modal">
                          <i class="fa-solid fa-xmark me-2"></i>Close
                        </button>
            
                        <button type="submit" class="btn btn-consultme-primary">
                          <i class="fa-solid fa-paper-plane me-2"></i>Submit Request
                        </button>
                      </div>
            
                    </form>
                  </div>
            
                </div>
              </div>
            </div>
            `;

          document.body.appendChild(modal);
        }

        // Activate modal on button click
        document.querySelectorAll('.book-btn').forEach(button => {
          button.addEventListener('click', (e) => {
            const consultant = data[e.target.dataset.index];
            document.getElementById('selectedConsultant').value = consultant.username;
            const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
            modal.show();
          });
        });

        // Handle form submission
        document.getElementById('appointmentForm').addEventListener('submit', async function (e) {
          e.preventDefault();
          const date = document.getElementById('appointmentDate').value;
          const time = document.getElementById('appointmentTime').value;
          const mode = document.getElementById('appointmentMode').value;
          const request = document.getElementById('appointmentRequest').value;

          const consultantUsername = document.getElementById('selectedConsultant').value;
          const selectedConsultant = data.find(consultant => consultant.username === consultantUsername);

          let user_username;
          let users_name;

          const user = await fetchUser();
          user_username = user.username;
          users_name = user.full_name;

          const payload = {
            consultant_username: selectedConsultant.username,
            consultant_name: selectedConsultant.name,
            user_username: user_username,
            users_name: users_name,
            appointment_date: date,
            appointment_time: time,
            mode_of_appointment: mode.toLowerCase(),
            user_request: request
          };

          fetch('/api/appointments/book', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
          })
            .then(res => res.json())
            .then(response => {
              if (response.success) {
                alert("Appointment booked successfully!");
                document.getElementById('appointmentForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('appointmentModal')).hide();
              } else {
                alert("Failed to book appointment: " + response.message);
              }
            })
            .catch(err => {
              console.error("Booking error:", err);
              alert("An error occurred while booking.");
            });
        });

      } else {
        container.innerHTML += '<p class="text-muted">No consultants found.</p>';
      }
    })
    .catch(error => {
      console.error('JS Fetch Error:', error);
      document.getElementById('consultants').innerHTML = '<p class="text-danger">Error fetching consultants.</p>';
    });
}
document.addEventListener('DOMContentLoaded', fetchConsultants);

document.addEventListener("click", function (e) {

  // View Profile Button Click
  if (e.target.classList.contains("view-profile-btn")) {

    const username = e.target.getAttribute("data-username");

    if (!username) {
      alert("Consultant username not found!");
      return;
    }

    window.open(`/consultant/profile/${encodeURIComponent(username)}`, "_blank");
  }

});



function fetchCategories() {
  fetch('/api/categories')
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        console.error("PHP Error:", data.error);
        container.innerHTML = `<p class="text-danger">${data.error}</p>`;
        return;
      }
      if (data.message) {
        container.innerHTML = `<p class="text-warning">${data.message}</p>`;
        return;
      }
      const container = document.getElementById('categories');
      container.innerHTML = ''; // Clear previous content

      const row = document.createElement('div');
      row.className = 'row gy-4'; // Adds gutter between rows

      data.forEach(category => {
        const col = document.createElement('div');
        col.className = 'col-md-4';

        const card = document.createElement('div');
        card.className = 'card shadow-lg border-0 rounded-4 h-100';
        card.style.background = 'linear-gradient(135deg, #ffffff, #f8f9fa)';

        const cardBody = document.createElement('div');
        cardBody.className = 'card-body d-flex flex-column justify-content-between';

        const cardTitle = document.createElement('h5');
        cardTitle.className = 'card-title fw-bold mb-3';
        cardTitle.textContent = `📂 ${category.name}`;

        const cardText = document.createElement('p');
        cardText.className = 'card-text mb-4 text-muted';
        cardText.textContent = '✨ Explore consultants in this category.';

        const btn = document.createElement('a');
        btn.className = 'btn btn-primary w-100 rounded-pill';
        btn.textContent = '🚀 Explore';
        btn.style.transition = '0.3s';
        btn.addEventListener('mouseenter', () => btn.style.transform = 'scale(1.05)');
        btn.addEventListener('mouseleave', () => btn.style.transform = 'scale(1)');

        btn.addEventListener('click', (e) => {
          e.preventDefault();
          console.log("🔍 Clicked category:", category.name);
          fetchConsultantsByCategory(category.name);
        });

        cardBody.appendChild(cardTitle);
        cardBody.appendChild(cardText);
        cardBody.appendChild(btn);

        card.appendChild(cardBody);
        col.appendChild(card);
        row.appendChild(col);
      });

      container.appendChild(row);
    })
    .catch(error => {
      console.error("JS Fetch Error:", error);
      document.getElementById('categories').innerHTML = '<p class="text-danger">Error fetching categories.</p>';
    });
}

//document.addEventListener('DOMContentLoaded', fetchCategories);

function fetchUser() {
  return fetch('/api/user/profile')
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        console.error(data.error);
        return null;
      }
      return {
        full_name: data.full_name,
        username: data.username
      };
    })
    .catch(err => {
      console.error("Error fetching user data:", err);
      return null;
    });
}

function fetchConsultantsByCategory(categoryName) {
  fetch(`/api/categories/consultants?category=${encodeURIComponent(categoryName)}`)
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById("consultants");
      container.innerHTML = `<h3>Consultants in "${categoryName}"</h3>`;

      if (data.error) {
        container.innerHTML += `<p class="text-danger">${data.error}</p>`;
        return;
      }

      if (data.length === 0) {
        container.innerHTML += `<p>No consultants found in this category.</p>`;
        return;
      }

      const row = document.createElement('div');
      row.className = 'row';

      data.forEach((c, index) => {
        const col = document.createElement('div');
        col.className = 'col-md-6 mb-4';
        col.innerHTML = `
            <style>
              /* ===============================
                 CONSULTME PREMIUM MINI PROFILE CARD
              =============================== */
            
              .consultant-mini-card {
                border-radius: 22px;
                border: 1px solid rgba(15, 23, 42, 0.10);
                background: rgba(255, 255, 255, 0.88);
                backdrop-filter: blur(14px);
                overflow: hidden;
                transition: 0.28s ease;
                box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
                position: relative;
                padding: 1.6rem;
              }
            
              .consultant-mini-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 28px 70px rgba(15, 23, 42, 0.14);
                border-color: rgba(37, 99, 235, 0.28);
              }
            
              .consultant-mini-card::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                  radial-gradient(circle at top left, rgba(37, 99, 235, 0.18), transparent 55%),
                  radial-gradient(circle at bottom right, rgba(15, 23, 42, 0.10), transparent 60%);
                opacity: 0.8;
                pointer-events: none;
                z-index: 0;
              }
            
              .consultant-mini-card * {
                position: relative;
                z-index: 2;
              }
            
              /* Avatar */
              .consultant-mini-avatar {
                width: 112px;
                height: 112px;
                border-radius: 50%;
                object-fit: cover;
                border: 5px solid rgba(255,255,255,0.95);
                box-shadow: 0 14px 40px rgba(15, 23, 42, 0.18);
                transition: 0.25s ease;
              }
            
              .consultant-mini-card:hover .consultant-mini-avatar {
                transform: scale(1.05);
              }
            
              /* Name */
              .consultant-mini-name {
                font-size: 1.15rem;
                font-weight: 900;
                color: #0f172a;
                letter-spacing: -0.4px;
                margin-top: 14px;
                margin-bottom: 2px;
              }
            
              .consultant-mini-username {
                font-size: 0.9rem;
                font-weight: 700;
                color: #64748b;
              }
            
              /* Location */
              .mini-location {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                margin-top: 10px;
                font-size: 0.88rem;
                font-weight: 700;
                color: #475569;
                padding: 8px 14px;
                border-radius: 50px;
                background: rgba(255,255,255,0.82);
                border: 1px solid rgba(15, 23, 42, 0.10);
                box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
              }
            
              .mini-location svg {
                width: 16px;
                height: 16px;
                color: #2563eb;
              }
            
              /* Details */
              .consultant-mini-details {
                margin-top: 18px;
                font-size: 0.93rem;
                color: #334155;
                line-height: 1.7;
              }
            
              .consultant-mini-details p {
                margin-bottom: 0.55rem;
              }
            
              .consultant-mini-details strong {
                color: #0f172a;
                font-weight: 900;
              }
            
              /* Bio clamp */
              .mini-bio {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
              }
            
              /* Divider */
              .mini-divider {
                height: 1px;
                margin: 1rem 0;
                background: linear-gradient(90deg,
                  rgba(15, 23, 42, 0.12),
                  rgba(15, 23, 42, 0.05),
                  rgba(15, 23, 42, 0.02)
                );
              }
            
              /* Premium Button */
              .mini-book-btn {
                font-weight: 900;
                padding: 0.75rem 1rem;
                border-radius: 14px;
                border: none;
                color: white;
                background: linear-gradient(90deg, #0d6efd, #2563eb, #0ea5e9);
                box-shadow: 0 18px 40px rgba(37, 99, 235, 0.28);
                transition: 0.22s ease;
              }
            
              .mini-book-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 24px 55px rgba(37, 99, 235, 0.35);
              }
            
              @media(max-width: 420px) {
                .consultant-mini-avatar {
                  width: 95px;
                  height: 95px;
                }
              }
            </style>
            
            <div class="consultant-mini-card h-100">
            
              <!-- TOP PROFILE -->
              <div class="text-center">
            
                <img src="/uploads/consultants/${c.profile_img || 'default.png'}"
                     class="consultant-mini-avatar"
                     alt="${c.name}" />
            
                <div class="consultant-mini-name">
                  ${c.name}
                </div>
            
                <div class="consultant-mini-username">
                  @${c.username}
                </div>
            
                <div class="mini-location">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                  </svg>
                  ${c.state || 'Not specified'}
                </div>
            
              </div>
            
              <div class="mini-divider"></div>
            
              <!-- DETAILS -->
              <div class="consultant-mini-details">
            
                <p>
                  <strong>Rate:</strong>
                  <span class="fw-bold text-primary">₹${c.hourly_rate || "0"}</span>
                  <span class="text-muted">/hour</span>
                </p>
            
                <p>
                  <strong>Expertise:</strong>
                  ${c.area_of_expertise || "-"}
                </p>
            
                <p>
                  <strong>Bio:</strong>
                  <span class="mini-bio">${c.bio || "No bio available."}</span>
                </p>
            
                <p class="mb-0">
                  <strong>Experience:</strong>
                  ${c.experience || "Not specified"}
                </p>
            
              </div>
            
              <!-- BUTTON -->
              <div class="d-grid mt-4">
                <button type="button" class="btn btn-primary rounded-pill book-btn" data-index="${index}"> 
                    <i class="fa-solid fa-calendar-check me-2"></i>Book Appointment
                </button>
              </div>
            
            </div>
            `;

        row.appendChild(col);
      });

      container.appendChild(row);
      showSection('consultants');

      // Create modal if not already added
      if (!document.getElementById('appointmentModal')) {
        const modal = document.createElement('div');
        modal.innerHTML = `
            <style>
              /* ===============================
                 CONSULTME PREMIUM MODAL DESIGN
              =============================== */
            
              #appointmentModal .modal-dialog {
                max-width: 520px;
              }
            
              #appointmentModal .modal-content {
                border-radius: 22px;
                border: 1px solid rgba(15, 23, 42, 0.12);
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(16px);
                box-shadow: 0 35px 90px rgba(15, 23, 42, 0.22);
                overflow: hidden;
              }
            
              /* Premium Header */
              #appointmentModal .modal-header {
                border: none;
                padding: 1.4rem 1.6rem;
                background: linear-gradient(135deg,
                  rgba(15, 23, 42, 0.04) 0%,
                  rgba(37, 99, 235, 0.12) 45%,
                  rgba(255, 255, 255, 0.9) 100%
                );
                border-bottom: 1px solid rgba(15, 23, 42, 0.08);
              }
            
              #appointmentModal .modal-title {
                font-weight: 900;
                font-size: 1.25rem;
                color: #0f172a;
                letter-spacing: -0.5px;
              }
            
              /* Body */
              #appointmentModal .modal-body {
                padding: 1.6rem;
              }
            
              /* Form Labels */
              #appointmentModal .form-label {
                font-weight: 800;
                color: #0f172a;
                font-size: 0.92rem;
                margin-bottom: 0.45rem;
              }
            
              /* Inputs */
              #appointmentModal .form-control,
              #appointmentModal .form-select {
                border-radius: 14px;
                padding: 0.8rem 1rem;
                border: 1px solid rgba(15, 23, 42, 0.14);
                background: rgba(255, 255, 255, 0.92);
                font-weight: 600;
                font-size: 0.95rem;
                color: #0f172a;
                transition: 0.2s ease;
              }
            
              #appointmentModal .form-control:focus,
              #appointmentModal .form-select:focus {
                border-color: rgba(37, 99, 235, 0.55);
                box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
              }
            
              #appointmentModal textarea.form-control {
                min-height: 110px;
                resize: none;
              }
            
              /* Label Icon Row */
              .label-row {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 6px;
              }
            
              .label-icon {
                width: 34px;
                height: 34px;
                border-radius: 10px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, rgba(37,99,235,0.16), rgba(255,255,255,0.8));
                border: 1px solid rgba(37,99,235,0.22);
                color: #2563eb;
                font-size: 14px;
                box-shadow: 0 10px 22px rgba(15,23,42,0.06);
              }
            
              /* Footer */
              #appointmentModal .modal-footer {
                border: none;
                padding: 1.2rem 1.6rem 1.6rem 1.6rem;
                display: flex;
                gap: 0.75rem;
              }
            
              /* Buttons */
              .btn-consultme-primary {
                flex: 1;
                border-radius: 14px;
                padding: 0.8rem 1rem;
                font-weight: 900;
                border: none;
                color: white;
                background: linear-gradient(90deg, #0d6efd, #2563eb, #0ea5e9);
                box-shadow: 0 18px 40px rgba(37, 99, 235, 0.28);
                transition: 0.2s ease;
              }
            
              .btn-consultme-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 24px 55px rgba(37, 99, 235, 0.35);
              }
            
              .btn-consultme-outline {
                flex: 1;
                border-radius: 14px;
                padding: 0.8rem 1rem;
                font-weight: 900;
                border: 1px solid rgba(15, 23, 42, 0.22);
                background: rgba(255,255,255,0.85);
                color: #0f172a;
                transition: 0.2s ease;
              }
            
              .btn-consultme-outline:hover {
                transform: translateY(-2px);
                background: rgba(15, 23, 42, 0.04);
                border-color: rgba(37, 99, 235, 0.40);
                color: #2563eb;
                box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
              }
            
              /* Modal smooth animation */
              .modal.fade .modal-dialog {
                transform: translateY(20px) scale(0.98);
                transition: transform 0.25s ease;
              }
            
              .modal.show .modal-dialog {
                transform: translateY(0px) scale(1);
              }
            
              @media(max-width: 520px) {
                #appointmentModal .modal-dialog {
                  margin: 0.8rem;
                }
            
                #appointmentModal .modal-footer {
                  flex-direction: column;
                }
              }
            </style>
            
            <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
            
                  <!-- HEADER -->
                  <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">
                      <i class="fa-solid fa-calendar-check text-primary me-2"></i>
                      Book Appointment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
            
                  <!-- BODY -->
                  <div class="modal-body">
                    <form id="appointmentForm">
            
                      <!-- Date -->
                      <div class="mb-3">
                        <div class="label-row">
                          <span class="label-icon"><i class="fa-regular fa-calendar"></i></span>
                          <label for="appointmentDate" class="form-label mb-0">Select Date</label>
                        </div>
                        <input type="date" class="form-control" id="appointmentDate" required>
                      </div>
            
                      <!-- Time -->
                      <div class="mb-3">
                        <div class="label-row">
                          <span class="label-icon"><i class="fa-regular fa-clock"></i></span>
                          <label for="appointmentTime" class="form-label mb-0">Select Time</label>
                        </div>
                        <input type="time" class="form-control" id="appointmentTime" required>
                      </div>
            
                      <!-- Mode -->
                      <div class="mb-3">
                        <div class="label-row">
                          <span class="label-icon"><i class="fa-solid fa-video"></i></span>
                          <label for="appointmentMode" class="form-label mb-0">Appointment Mode</label>
                        </div>
            
                        <select class="form-select" id="appointmentMode" required>
                          <option value="">Choose Mode</option>
                          <option value="Online">Online (Zoom / Google Meet)</option>
                          <option value="Offline">Offline (In-person)</option>
                        </select>
                      </div>
            
                      <!-- Requests -->
                      <div class="mb-3">
                        <div class="label-row">
                          <span class="label-icon"><i class="fa-solid fa-pen-to-square"></i></span>
                          <label for="appointmentRequest" class="form-label mb-0">Special Request (Optional)</label>
                        </div>
            
                        <textarea class="form-control" id="appointmentRequest" rows="3"
                          placeholder="Write your requirement briefly..."></textarea>
                      </div>
            
                      <input type="hidden" id="selectedConsultant">
            
                      <!-- FOOTER -->
                      <div class="modal-footer p-0 pt-3">
                        <button type="button" class="btn btn-consultme-outline" data-bs-dismiss="modal">
                          <i class="fa-solid fa-xmark me-2"></i>Close
                        </button>
            
                        <button type="submit" class="btn btn-consultme-primary">
                          <i class="fa-solid fa-paper-plane me-2"></i>Submit Request
                        </button>
                      </div>
            
                    </form>
                  </div>
            
                </div>
              </div>
            </div>

        `;
        document.body.appendChild(modal);
      }

      // Attach click listeners to open modal
      document.querySelectorAll('.book-btn').forEach(button => {
        button.addEventListener('click', (e) => {
          const consultant = data[e.target.dataset.index];

          // Store JSON string in the hidden input safely
          document.getElementById('selectedConsultant').value = JSON.stringify({
            username: consultant.username,
            name: consultant.name
          });

          const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
          modal.show();
        });
      });

      // Attach submit handler once (prevent duplicate attachment)
      const appointmentForm = document.getElementById('appointmentForm');
      if (!appointmentForm.hasAttribute('data-listener-added')) {
        appointmentForm.setAttribute('data-listener-added', 'true');
        appointmentForm.addEventListener('submit', async function (e) {
          e.preventDefault();

          const date = document.getElementById('appointmentDate').value;
          const time = document.getElementById('appointmentTime').value;
          const mode = document.getElementById('appointmentMode').value;
          const request = document.getElementById('appointmentRequest').value;

          const consultantData = JSON.parse(document.getElementById('selectedConsultant').value);
          const consultantUsername = consultantData.username;
          const consultantName = consultantData.name;

          const user = await fetchUser();
          if (!user) {
            alert("User not found.");
            return;
          }

          const payload = {
            consultant_username: consultantUsername,
            consultant_name: consultantName,
            user_username: user.username,
            users_name: user.full_name,
            appointment_date: date,
            appointment_time: time,
            mode_of_appointment: mode.toLowerCase(),
            user_request: request
          };

          fetch('/api/appointments/book', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          })
            .then(res => res.json())
            .then(response => {
              if (response.success) {
                alert("Appointment booked successfully!");
                appointmentForm.reset();
                bootstrap.Modal.getInstance(document.getElementById('appointmentModal')).hide();
              } else {
                alert("Failed to book appointment: " + response.message);
              }
            })
            .catch(err => {
              console.error("Booking error:", err);
              alert("An error occurred while booking.");
            });
        });
      }
    })
    .catch(err => {
      console.error("Fetch error:", err);
      document.getElementById("consultants").innerHTML =
        `<p class="text-danger">Error fetching consultants.</p>`;
    });
}

function fetchAppointments() {
  fetch('/api/user/appointments')
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('appointments');
        container.innerHTML = '';

        /* ===============================
           CONSULTME PREMIUM TABLE CSS
        ================================ */
        const style = document.createElement("style");
        style.innerHTML = `
          .consultme-table-wrap{
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(15, 23, 42, 0.10);
            border-radius: 22px;
            padding: 20px;
            box-shadow: 0 22px 60px rgba(15, 23, 42, 0.10);
            overflow: hidden;
            margin-top: 12px;
          }
        
          .consultme-table-title{
            font-weight: 900;
            font-size: 1.3rem;
            color: #0f172a;
            letter-spacing: -0.4px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
          }
        
          .consultme-table-title i{
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(37,99,235,0.18), rgba(255,255,255,0.85));
            border: 1px solid rgba(37,99,235,0.22);
            color: #2563eb;
            box-shadow: 0 10px 22px rgba(15,23,42,0.06);
          }
        
          .consultme-table{
            margin: 0;
            border-collapse: separate;
            border-spacing: 0 10px;
          }
        
          .consultme-table thead th{
            background: linear-gradient(135deg, rgba(15,23,42,0.06), rgba(37,99,235,0.10));
            border: none !important;
            padding: 14px 14px;
            font-weight: 900;
            color: #0f172a;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            white-space: nowrap;
          }
        
          .consultme-table tbody tr{
            background: rgba(255,255,255,0.90);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 14px 35px rgba(15,23,42,0.08);
            transition: 0.25s ease;
          }
        
          .consultme-table tbody tr:hover{
            transform: translateY(-3px);
            box-shadow: 0 22px 55px rgba(15,23,42,0.12);
          }
        
          .consultme-table tbody td{
            border: none !important;
            padding: 14px 14px;
            vertical-align: middle;
            font-weight: 600;
            color: #334155;
            font-size: 0.93rem;
          }
        
          .consultme-table tbody td:first-child{
            font-weight: 900;
            color: #0f172a;
          }
        
          .consultme-table tbody td:nth-child(2){
            font-weight: 900;
            color: #0f172a;
          }
        
          .consultme-table tbody td a{
            text-decoration: none;
            font-weight: 900;
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: rgba(37,99,235,0.10);
            border: 1px solid rgba(37,99,235,0.18);
            transition: 0.2s ease;
          }
        
          .consultme-table tbody td a:hover{
            background: rgba(37,99,235,0.18);
            transform: translateY(-2px);
          }
        
          .consultme-table .badge{
            padding: 10px 14px;
            border-radius: 50px;
            font-weight: 900;
            font-size: 0.82rem;
            letter-spacing: 0.4px;
            box-shadow: 0 10px 22px rgba(15,23,42,0.08);
          }
        
          .consultme-table .cancel-btn{
            border-radius: 14px;
            font-weight: 900;
            padding: 0.55rem 0.85rem;
            transition: 0.2s ease;
          }
        
          .consultme-table .cancel-btn:hover{
            transform: translateY(-2px);
            box-shadow: 0 14px 35px rgba(239,68,68,0.18);
          }
        
          .consultme-empty-row td{
            background: rgba(255,255,255,0.75);
            border-radius: 16px;
            padding: 18px !important;
            font-weight: 800;
            color: #64748b;
            text-align: center;
            box-shadow: 0 14px 35px rgba(15,23,42,0.06);
          }
        
          .consultme-error-row td{
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.18);
            border-radius: 16px;
            padding: 18px !important;
            font-weight: 900;
            color: #dc2626;
            text-align: center;
          }
        
          @media(max-width: 992px){
            .consultme-table-wrap{
              padding: 14px;
            }
            .consultme-table thead{
              display: none;
            }
            .consultme-table tbody tr{
              display: block;
              padding: 14px;
            }
            .consultme-table tbody td{
              display: flex;
              justify-content: space-between;
              gap: 10px;
              padding: 10px 6px;
            }
            .consultme-table tbody td::before{
              content: attr(data-label);
              font-weight: 900;
              color: #0f172a;
              min-width: 120px;
            }
          }
        `;
        document.head.appendChild(style);
        
        
        /* ===============================
           TITLE (Same logic)
        ================================ */
        const title = document.createElement('h4');
        title.className = "consultme-table-title";
        title.innerHTML = `<i class="fa-solid fa-calendar-check"></i> My Appointments`;
        container.appendChild(title);
        
        
        /* ===============================
           TABLE WRAPPER (Design Only)
        ================================ */
        const wrapper = document.createElement("div");
        wrapper.className = "consultme-table-wrap";
        container.appendChild(wrapper);
        
        
        /* ===============================
           TABLE (Same logic)
        ================================ */
        const table = document.createElement('table');
        table.className = 'table consultme-table';
        wrapper.appendChild(table);
        
        
        /* ===============================
           THEAD (Same logic)
        ================================ */
        const thead = document.createElement('thead');
        thead.innerHTML = `
          <tr>
            <th>Id</th>
            <th>Consultant</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>My Request</th>
            <th>Consultant Note</th>
            <th>Meeting Link</th>
            <th>Action</th>
          </tr>`;
        table.appendChild(thead);
        
        
        /* ===============================
           TBODY (Same logic)
        ================================ */
        const tbody = document.createElement('tbody');
        
        if (data.error) {
          tbody.innerHTML = `<tr class="consultme-error-row"><td colspan="9">${data.error}</td></tr>`;
        } else if (data.length === 0) {
          tbody.innerHTML = `<tr class="consultme-empty-row"><td colspan="9">No appointments found.</td></tr>`;
        } else {
          data.forEach(app => {
            const row = document.createElement('tr');
        
            row.innerHTML = `
              <td data-label="Id">${app.appointment_id}</td>
              <td data-label="Consultant">${app.consultant_name}</td>
              <td data-label="Date">${app.appointment_date}</td>
              <td data-label="Time">${formatTime(app.appointment_time) || '-'}</td>
              <td data-label="Status">
                <span class="badge bg-${getStatusColor(app.status)}">
                  ${capitalize(app.status)}
                </span>
              </td>
              <td data-label="My Request">${app.user_request}</td>
              <td data-label="Consultant Note">${app.consultant_message || '-'}</td>
              <td data-label="Meeting Link">
                ${app.meeting_link ? `
                  <a href="${app.meeting_link}" target="_blank" rel="noopener noreferrer" title="Join Meeting">
                    <i class="fa-solid fa-link"></i>
                  </a>` : '-'}
              </td>
              <td data-label="Action">
                ${(app.status === 'pending' || app.status === 'confirmed') ? `
                  <button class="btn btn-sm btn-outline-danger cancel-btn" data-id="${app.appointment_id}">
                    <i class="fa-solid fa-ban me-1"></i> Cancel
                  </button>
                ` : '-'}
              </td>
            `;
        
            tbody.appendChild(row);
          });
        }


      table.appendChild(tbody);
      container.appendChild(table);

      // Attach Cancel button handlers
      container.querySelectorAll('.cancel-btn').forEach(button => {
        button.addEventListener('click', () => {
          const appointmentId = button.getAttribute('data-id');
          console.log("Cancelling appointment:", appointmentId);

          if (confirm("Are you sure you want to cancel this appointment?")) {
            fetch('/api/appointments/status', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                appointment_id: appointmentId,
                new_status: 'cancelled'
              })
            })
              .then(res => res.json())
              .then(response => {
                if (response.success) {
                  alert("Appointment cancelled successfully.");
                  fetchAppointments();
                } else {
                  alert("Failed to cancel appointment: " + (response.message || 'Unknown error'));
                }
              })
              .catch(err => {
                console.error("Cancel Error:", err);
                alert("Something went wrong.");
              });
          }
        });
      });
    })
    .catch(err => {
      console.error("Appointment Fetch Error:", err);
      const container = document.getElementById('appointments');
      container.innerHTML = `<p class="text-danger">Failed to load appointments.</p>`;
    });
}
document.addEventListener('DOMContentLoaded', fetchAppointments);

function getStatusColor(status) {
  switch (status) {
    case 'confirmed': return 'success';
    case 'pending': return 'warning';
    case 'cancelled': return 'danger';
    case 'completed': return 'primary';
    default: return 'secondary';
  }
}

function capitalize(word) {
  return word.charAt(0).toUpperCase() + word.slice(1);
}

function formatTime(mysqlTime) {
  if (!mysqlTime) return '-';
  return mysqlTime.split('.')[0].slice(0, 5);
}

function fetchHistory() {
  fetch("/api/user/appointments/history")
    .then(res => res.json())
    .then(data => {
      const historyContainer = document.getElementById("history");
      historyContainer.innerHTML = "";

            /* ===============================
               CONSULTME PREMIUM HISTORY TABLE CSS
            ================================ */
            const style = document.createElement("style");
            style.innerHTML = `
              .consultme-history-wrap{
                background: rgba(255,255,255,0.88);
                backdrop-filter: blur(14px);
                border: 1px solid rgba(15, 23, 42, 0.10);
                border-radius: 22px;
                padding: 20px;
                box-shadow: 0 22px 60px rgba(15, 23, 42, 0.10);
                overflow: hidden;
                margin-top: 10px;
              }
            
              .consultme-history-title{
                font-weight: 900;
                font-size: 1.25rem;
                color: #0f172a;
                letter-spacing: -0.4px;
                margin-bottom: 16px;
                display: flex;
                align-items: center;
                gap: 10px;
              }
            
              .consultme-history-title i{
                width: 38px;
                height: 38px;
                border-radius: 12px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, rgba(37,99,235,0.18), rgba(255,255,255,0.85));
                border: 1px solid rgba(37,99,235,0.22);
                color: #2563eb;
                box-shadow: 0 10px 22px rgba(15,23,42,0.06);
              }
            
              .consultme-history-table{
                margin: 0;
                border-collapse: separate;
                border-spacing: 0 12px;
              }
            
              .consultme-history-table thead th{
                background: linear-gradient(135deg, rgba(15,23,42,0.06), rgba(37,99,235,0.10));
                border: none !important;
                padding: 14px 14px;
                font-weight: 900;
                color: #0f172a;
                font-size: 0.88rem;
                text-transform: uppercase;
                letter-spacing: 0.6px;
                white-space: nowrap;
              }
            
              .consultme-history-table tbody tr{
                background: rgba(255,255,255,0.92);
                border-radius: 18px;
                box-shadow: 0 14px 35px rgba(15,23,42,0.08);
                transition: 0.25s ease;
              }
            
              .consultme-history-table tbody tr:hover{
                transform: translateY(-3px);
                box-shadow: 0 22px 55px rgba(15,23,42,0.12);
              }
            
              .consultme-history-table tbody td{
                border: none !important;
                padding: 14px 14px;
                vertical-align: middle;
                font-weight: 600;
                color: #334155;
                font-size: 0.93rem;
              }
            
              .consultme-history-table tbody td strong{
                font-weight: 900;
                color: #0f172a;
              }
            
              /* Premium Badge */
              .consultme-status-badge{
                padding: 10px 14px;
                border-radius: 50px;
                font-weight: 900;
                font-size: 0.82rem;
                letter-spacing: 0.4px;
                box-shadow: 0 10px 22px rgba(15,23,42,0.08);
                display: inline-flex;
                align-items: center;
                gap: 8px;
              }
            
              .consultme-status-badge.completed{
                background: linear-gradient(135deg, rgba(34,197,94,0.18), rgba(255,255,255,0.85));
                border: 1px solid rgba(34,197,94,0.30);
                color: #166534;
              }
            
              .consultme-status-badge.pending{
                background: linear-gradient(135deg, rgba(245,158,11,0.18), rgba(255,255,255,0.85));
                border: 1px solid rgba(245,158,11,0.35);
                color: #92400e;
              }
            
              .consultme-status-badge i{
                font-size: 0.9rem;
              }
            
              /* Rating Column */
              .rating-feedback{
                min-width: 260px;
              }
            
              .rating-feedback select,
              .rating-feedback textarea{
                border-radius: 14px !important;
                border: 1px solid rgba(15, 23, 42, 0.14) !important;
                background: rgba(255,255,255,0.95) !important;
                font-weight: 700;
                font-size: 0.9rem;
                transition: 0.2s ease;
              }
            
              .rating-feedback select:focus,
              .rating-feedback textarea:focus{
                border-color: rgba(37, 99, 235, 0.55) !important;
                box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15) !important;
              }
            
              .rating-feedback textarea{
                resize: none;
              }
            
              /* Premium Submit Button */
              .rating-feedback button{
                border-radius: 14px !important;
                font-weight: 900 !important;
                padding: 0.55rem 0.9rem !important;
                transition: 0.2s ease;
              }
            
              .rating-feedback button:hover{
                transform: translateY(-2px);
                box-shadow: 0 18px 40px rgba(37, 99, 235, 0.18);
              }
            
              /* Rated Text */
              .rating-feedback p{
                margin-bottom: 6px;
                font-size: 0.92rem;
                color: #334155;
                line-height: 1.6;
              }
            
              /* Responsive Card View */
              @media(max-width: 992px){
                .consultme-history-table thead{
                  display: none;
                }
            
                .consultme-history-table tbody tr{
                  display: block;
                  padding: 14px;
                }
            
                .consultme-history-table tbody td{
                  display: flex;
                  justify-content: space-between;
                  gap: 10px;
                  padding: 10px 6px;
                }
            
                .consultme-history-table tbody td::before{
                  content: attr(data-label);
                  font-weight: 900;
                  color: #0f172a;
                  min-width: 140px;
                }
            
                .rating-feedback{
                  min-width: unset;
                }
              }
            `;
            document.head.appendChild(style);
            
            
            /* ===============================
               WRAPPER (Design only)
            ================================ */
            const wrapper = document.createElement("div");
            wrapper.className = "consultme-history-wrap";
            historyContainer.appendChild(wrapper);
            
            
            /* Optional Title (Design only) */
            const heading = document.createElement("div");
            heading.className = "consultme-history-title";
            heading.innerHTML = `<i class="fa-solid fa-clock-rotate-left"></i> Appointment History`;
            wrapper.appendChild(heading);
            
            
            /* ===============================
               TABLE (Same logic)
            ================================ */
            const table = document.createElement("table");
            table.className = "table consultme-history-table mb-0";
            
            table.innerHTML = `
              <thead>
                <tr>
                  <th>Appointment ID</th>
                  <th>Consultant</th>
                  <th>Status</th>
                  <th>Date</th>
                  <th>Rating / Feedback</th>
                </tr>
              </thead>
              <tbody>
                ${data.map(item => `
                  <tr>
                    <td data-label="Appointment ID"><strong>#${item.appointment_id}</strong></td>
            
                    <td data-label="Consultant">
                      <strong>${item.consultant_name}</strong>
                    </td>
            
                    <td data-label="Status">
                      <span class="consultme-status-badge ${item.status === "completed" ? "completed" : "pending"}">
                        <i class="fa-solid ${item.status === "completed" ? "fa-circle-check" : "fa-hourglass-half"}"></i>
                        ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                      </span>
                    </td>
            
                    <td data-label="Date">
                      ${new Date(item.appointment_date).toLocaleDateString()}
                    </td>
            
                    <td data-label="Rating / Feedback" class="rating-feedback">
                      ${item.status === "completed" && !item.rating_given ? `
                        <div class="mb-2">
                          <select id="rating-${item.appointment_id}" class="form-select form-select-sm">
                            <option value="">&#x2606; Rate</option>
                            <option value="1">&#x2605;</option>
                            <option value="2">&#x2605;&#x2605;</option>
                            <option value="3">&#x2605;&#x2605;&#x2605;</option>
                            <option value="4">&#x2605;&#x2605;&#x2605;&#x2605;</option>
                            <option value="5">&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</option>
                          </select>
                        </div>
            
                        <textarea id="feedback-${item.appointment_id}"
                                  class="form-control form-control-sm mb-2"
                                  rows="2"
                                  placeholder="Write feedback..."></textarea>
            
                        <button class="btn btn-outline-primary btn-sm"
                          onclick="submitRating('${item.appointment_id}', '${item.consultant_username}', '${item.user_username}')">
                          <i class="fa-solid fa-paper-plane me-1"></i> Submit
                        </button>
                      ` : item.status === "completed" ? `
                        <p class="mb-1"><strong>Rated:</strong> ${item.user_rating} / 5</p>
                        <p class="mb-0"><strong>Feedback:</strong> ${item.feedback}</p>
                      ` : `<span class="text-muted fw-bold">N/A</span>`}
                    </td>
                  </tr>
                `).join('')}
              </tbody>
        `;


      historyContainer.appendChild(table);
    });
}

function submitRating(appointmentId, consultantUsername, userName) {
  const rating = document.getElementById(`rating-${appointmentId}`).value;
  const feedback = document.getElementById(`feedback-${appointmentId}`).value;

  if (!rating) {
    alert("Please select a rating");
    return;
  }

  fetch("/api/ratings", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `appointment_id=${appointmentId}&consultant_username=${consultantUsername}&user_username=${userName}&rating=${rating}&feedback=${encodeURIComponent(feedback)}`
  })
    .then(res => res.json())
    .then(response => {
      if (response.status === "success") {
        alert("Rating submitted successfully!");
        fetchHistory();
      } else {
        alert("Error: " + response.message);
      }
    });
}

function fetchUserDetails() {
  fetch('/api/user/profile')
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        alert(data.error);
        return;
      }
      document.getElementById('user-name').textContent = data.full_name;
      document.getElementById('user-username').textContent = data.username;
      document.getElementById('user-phone').textContent = data.phone;
      document.getElementById('user-email').textContent = data.email;
      document.getElementById('user-identity').textContent = data.identity;
      document.getElementById('user-interest1').textContent = data.interest1;
      document.getElementById('user-interest2').textContent = data.interest2;
      document.getElementById('user-interest3').textContent = data.interest3;
      document.getElementById('user-state').textContent = data.state;
      document.getElementById('user-profile-img').src = data.profile_img;
    })
    .catch(err => {
      console.error('Fetch error:', err);
    });
}

// Open edit form with existing data
function openEditForm() {
  document.getElementById('edit-profile-form').style.display = 'block';
  document.getElementById('edit-fullname').value = document.getElementById('user-name').textContent;
  document.getElementById('edit-identity').value = document.getElementById('user-identity').textContent;
  document.getElementById('edit-phone').value = document.getElementById('user-phone').textContent;
  document.getElementById('edit-state').value = document.getElementById('user-state').textContent;
  document.getElementById('edit-identity').value = document.getElementById('user-identity').textContent;
  document.getElementById('edit-interest1').value = document.getElementById('user-interest1').textContent;
  document.getElementById('edit-interest2').value = document.getElementById('user-interest2').textContent;
  document.getElementById('edit-interest3').value = document.getElementById('user-interest3').textContent;
}

function closeEditForm() {
  document.getElementById('edit-profile-form').style.display = 'none';
}

// Save user profile
function saveUserProfile() {
  const formData = new FormData();

  formData.append('full_name', document.querySelector('[name="full_name"]').value);
  formData.append('identity', document.querySelector('[name="identity"]').value);
  formData.append('phone', document.querySelector('[name="phone"]').value);
  formData.append('state', document.querySelector('[name="state"]').value);
  formData.append('interest1', document.querySelector('[name="interest1"]').value);
  formData.append('interest2', document.querySelector('[name="interest2"]').value);
  formData.append('interest3', document.querySelector('[name="interest3"]').value);

  const imageInput = document.querySelector('[name="profile_image"]');
  if (imageInput && imageInput.files.length > 0) {
    formData.append('profile_image', imageInput.files[0]);
  }

  // Progress UI
  const container = document.getElementById("userUploadProgress");
  const bar = document.getElementById("userUploadBar");
  const text = document.getElementById("userUploadText");
  const saveBtn = document.querySelector('#edit-profile-form .btn-primary');

  if (container) container.style.display = "block";
  if (bar) bar.style.width = "0%";
  if (text) text.innerText = "Uploading... 0%";
  if (saveBtn) saveBtn.disabled = true;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/api/user/profile", true);

  // ✅ Upload progress
  xhr.upload.onprogress = function (e) {
    if (e.lengthComputable) {
      let percent = Math.round((e.loaded / e.total) * 100);

      if (bar) bar.style.width = percent + "%";
      if (text) text.innerText = "Uploading... " + percent + "%";
    }
  };

  // ✅ Success
  xhr.onload = function () {
    if (container) container.style.display = "none";
    if (saveBtn) saveBtn.disabled = false;

    let response;
    try {
      response = JSON.parse(xhr.responseText);
    } catch {
      alert("Invalid server response");
      return;
    }

    console.log("Server response:", response);

    if (response.success) {
      alert('Profile updated successfully!');
      closeEditForm();
      fetchUserDetails();

      const img = document.getElementById('user-profile-img');
      if (img && img.src) {
        img.src = img.src.split('?')[0] + '?t=' + new Date().getTime();
      }
    } else {
      alert('Update failed: ' + (response.message || 'Unknown error'));
    }
  };

  // ❌ Error
  xhr.onerror = function () {
    if (container) container.style.display = "none";
    if (saveBtn) saveBtn.disabled = false;

    alert("Network error during upload");
  };

  xhr.send(formData);
}

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('edit-profile-form');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      saveUserProfile();
    });
  }
});

/* =======================================================
   PROJECTS MODULE (USER: PROJECTS + APPLICATIONS)
======================================================= */

console.log("%c[Projects Module Loaded]", "color: green; font-weight:bold;");

const PROJECT_FETCH   = "/api/projects";
const PROJECT_CREATE  = "/api/projects";
const PROJECT_APPS    = "/api/projects/applications";

let projectsCurrentFilter = 'active';
let projectsCurrentPage   = 1;
let projectsById          = {};
let currentProjectApplications = []; // used by View Profile modal

/* ---------- Small helpers (safe to keep once globally) ---------- */
function escapeHtml(str) {
  if (str === null || str === undefined) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

function formatDateShort(dtStr) {
  if (!dtStr) return 'N/A';
  const d = new Date(dtStr);
  if (isNaN(d.getTime())) return escapeHtml(dtStr);
  return d.toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
}

/* ---------- Post Project Modal ---------- */
function openPostProjectModal() {
  console.log("[projects] Opening Post Project Modal");
  const modal = bootstrap.Modal.getOrCreateInstance(
    document.getElementById("postProjectModal")
  );
  modal.show();
}

function closePostProjectModal() {
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("postProjectModal")
  );
  if (modal) modal.hide();
}

/* ---------- Load Projects ---------- */
function loadProjects(filter = 'active', page = 1) {
  projectsCurrentFilter = filter;
  projectsCurrentPage   = page;

  console.log("[projects] Loading:", filter, "Page:", page);

  const container  = document.getElementById("projectsContainer");
  const pagination = document.getElementById("projectsPagination");

  if (!container) {
    console.error("[projects] projectsContainer NOT FOUND.");
    return;
  }

  container.innerHTML  = "<p class='text-muted'>Loading projects...</p>";
  if (pagination) pagination.innerHTML = "";

  const tabActive  = document.getElementById('projectsTabActive');
  const tabHistory = document.getElementById('projectsTabHistory');
  if (tabActive && tabHistory) {
    tabActive.classList.toggle('active',  filter === 'active');
    tabHistory.classList.toggle('active', filter === 'history');
  }

  fetch(`${PROJECT_FETCH}?filter=${encodeURIComponent(filter)}&page=${page}`)
    .then(res => res.json())
    .then(json => {
      console.log("[projects] Fetch result:", json);

      if (!json.success) {
        container.innerHTML =
          `<p class="text-danger">${escapeHtml(json.message || 'Failed to load projects.')}</p>`;
        return;
      }

      projectsById = {};
      (json.projects || []).forEach(p => {
        if (!p) return;
        const pid = String(p.project_id || p.id || '');
        if (pid) projectsById[pid] = p;
      });

      renderProjectCards(json.projects || []);
      renderProjectPagination(json.page || 1, json.per_page || 10, json.total || 0);
    })
    .catch(err => {
      console.error("[projects] Fetch error:", err);
      container.innerHTML = `<p class="text-danger">Failed to load projects.</p>`;
    });
}

/* ---------- Render Project Cards ---------- */
function renderProjectCards(projects) {
  const container = document.getElementById("projectsContainer");
  if (!container) return;

  if (!projects || projects.length === 0) {
    container.innerHTML = "<p class='text-muted'>No projects found.</p>";
    return;
  }

  let html = '';

  projects.forEach(p => {
    const pid    = escapeHtml(p.project_id || p.id || '');
    const title  = escapeHtml(p.title || 'Untitled Project');
    const posted = formatDateShort(p.posted_at || p.created_at);
    const poster = escapeHtml(p.poster_name || p.username || 'You');
    const budget = p.budget ? escapeHtml(p.budget) : '';
    const billing= p.billing_cycle ? escapeHtml(p.billing_cycle) : '';

    const raw = (p.short_description && p.short_description.trim().length)
      ? p.short_description.trim()
      : (p.description || '').trim();
    const trimmed = raw.length > 220
      ? escapeHtml(raw.slice(0, 220)) + '…'
      : escapeHtml(raw);

    let daysHtml = '';
    if (projectsCurrentFilter === "active" && p.expiry_date) {
      const now  = new Date();
      const exp  = new Date(p.expiry_date + 'T23:59:59');
      const diff = Math.ceil((exp - now) / 86400000);
      if (!isNaN(diff) && diff >= 0) {
        daysHtml = `
          <span class="badge-days" title="Time remaining">
            ${diff} day${diff !== 1 ? 's' : ''} left
          </span>`;
      }
    }

    html += `
        <style>
          /* ===============================
             CONSULTME PREMIUM PROJECT CARD
          ================================ */
        
          .project-card {
            border-radius: 22px;
            border: 1px solid rgba(15, 23, 42, 0.10);
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(14px);
            box-shadow: 0 18px 55px rgba(15, 23, 42, 0.10);
            overflow: hidden;
            transition: 0.28s ease;
            position: relative;
          }
        
          .project-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.16);
            border-color: rgba(37, 99, 235, 0.28);
          }
        
          /* subtle glow */
          .project-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
              radial-gradient(circle at top left, rgba(37, 99, 235, 0.18), transparent 55%),
              radial-gradient(circle at bottom right, rgba(15, 23, 42, 0.10), transparent 65%);
            opacity: 0.85;
            pointer-events: none;
            z-index: 0;
          }
        
          .project-card * {
            position: relative;
            z-index: 2;
          }
        
          .project-card .card-inner {
            padding: 1.5rem 1.5rem 1.2rem 1.5rem;
          }
        
          .project-card .card-header {
            padding-bottom: 0.9rem;
            margin-bottom: 0.9rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
          }
        
          .project-card .card-title {
            font-size: 1.15rem;
            font-weight: 900;
            letter-spacing: -0.4px;
            color: #0f172a;
            margin-bottom: 0.3rem;
          }
        
          .project-card .meta-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
          }
        
          .project-card .poster {
            font-weight: 800;
            color: #334155;
            font-size: 0.92rem;
          }
        
          .project-card .icon {
            width: 18px;
            height: 18px;
            opacity: 0.9;
          }
        
          /* Pills */
          .project-card .pills {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
          }
        
          .project-card .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 0.9rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 900;
            color: #0f172a;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
            transition: 0.2s ease;
            white-space: nowrap;
          }
        
          .project-card .pill:hover {
            transform: translateY(-2px);
            border-color: rgba(37, 99, 235, 0.30);
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.10);
          }
        
          .project-card .pill svg {
            width: 16px;
            height: 16px;
          }
        
          /* Description */
          .project-card .desc {
            margin-top: 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #475569;
            line-height: 1.75;
        
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
          }
        
          /* Footer */
          .project-card .card-footer {
            padding: 1rem 1.5rem 1.4rem 1.5rem;
            border-top: 1px solid rgba(15, 23, 42, 0.08);
            background: linear-gradient(
              135deg,
              rgba(15, 23, 42, 0.02),
              rgba(37, 99, 235, 0.04)
            );
          }
        
          /* Button upgrade */
          .project-card .btn-outline-primary {
            border-radius: 14px !important;
            font-weight: 900 !important;
            padding: 0.55rem 1.1rem !important;
            border: 1px solid rgba(37, 99, 235, 0.28) !important;
            color: #2563eb !important;
            background: rgba(255, 255, 255, 0.80) !important;
            transition: 0.22s ease;
          }
        
          .project-card .btn-outline-primary:hover {
            transform: translateY(-2px);
            background: linear-gradient(90deg, #0d6efd, #2563eb, #0ea5e9) !important;
            border-color: transparent !important;
            color: white !important;
            box-shadow: 0 18px 45px rgba(37, 99, 235, 0.28);
          }
        
          /* Responsive */
          @media (max-width: 520px) {
            .project-card .card-inner {
              padding: 1.2rem;
            }
        
            .project-card .card-footer {
              padding: 1rem 1.2rem 1.2rem 1.2rem;
            }
        
            .project-card .card-title {
              font-size: 1.05rem;
            }
          }
        </style>
        
        <article class="project-card" data-project-id="${pid}">
          <div class="card-inner">
        
            <div class="card-header">
              <h3 class="card-title">${title}</h3>
              <div class="meta-row">
                <small class="text-muted fw-semibold">${posted}</small>
              </div>
            </div>
        
            <div class="meta-row" style="justify-content:flex-start;">
              <div style="display:flex;align-items:center;gap:.45rem;">
                <svg class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zM4 20c0-3.313 2.687-6 6-6h4c3.313 0 6 2.687 6 6"
                        stroke="#6b7280" stroke-width="1.25"
                        stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="poster">${poster}</span>
              </div>
        
              <div class="pills" style="margin-left:auto;">
                ${budget ? `
                  <span class="pill" title="Budget">
                    <svg class="icon" viewBox="0 0 24 24">
                      <path d="M21 10v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6"
                            stroke="#0b1220" stroke-width="1.2" fill="none"></path>
                      <rect x="2" y="7" width="20" height="4" rx="1"
                            stroke="#0b1220" stroke-width="1.2" fill="none"></rect>
                    </svg>
                    ${budget}
                  </span>` : ''}
        
                ${billing ? `
                  <span class="pill" title="Billing cycle">
                    <svg class="icon" viewBox="0 0 24 24">
                      <circle cx="12" cy="12" r="9"
                              stroke="#0b1220" stroke-width="1.2" fill="none"></circle>
                      <path d="M12 8v4l3 3"
                            stroke="#0b1220" stroke-width="1.2" fill="none"></path>
                    </svg>
                    ${billing}
                  </span>` : ''}
              </div>
            </div>
        
            <p class="desc" aria-label="Project description">${trimmed}</p>
        
          </div>
        
          <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>${daysHtml}</div>
        
            <button type="button"
                    class="btn btn-sm btn-outline-primary rounded-pill"
                    onclick="openProjectDetails('${pid}')">
              View
            </button>
          </div>
        </article>
    `;
  });

  container.innerHTML = html;
}

/* ---------- Pagination ---------- */
function renderProjectPagination(page, per_page, total) {
  const el = document.getElementById("projectsPagination");
  if (!el) return;

  const totalPages = Math.max(1, Math.ceil(total / per_page));
  let html = `<span class="text-muted small">Page ${page} of ${totalPages}</span>`;

  if (page > 1) {
    html += `
      <button class="btn btn-sm btn-outline-secondary ms-2 rounded-pill"
              onclick="loadProjects('${projectsCurrentFilter}', ${page - 1})">
        Prev
      </button>`;
  }

  if (page < totalPages) {
    html += `
      <button class="btn btn-sm btn-outline-secondary ms-2 rounded-pill"
              onclick="loadProjects('${projectsCurrentFilter}', ${page + 1})">
        Next
      </button>`;
  }

  el.innerHTML = html;
}

/* ---------- Project Details + Applications Modal ---------- */
let projectDetailsModalInitialized = false;

function ensureProjectDetailsModal() {
  if (projectDetailsModalInitialized) return;
  projectDetailsModalInitialized = true;

    const html = `
        <style>
          /* ===============================
             CONSULTME PREMIUM PROJECT MODAL
          =============================== */
        
          #projectDetailsModal .modal-dialog {
            max-width: 980px;
          }
        
          #projectDetailsModal .modal-content {
            border-radius: 22px !important;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(18px);
            box-shadow: 0 40px 110px rgba(15, 23, 42, 0.22);
            overflow: hidden;
          }
        
          /* Header */
          #projectDetailsModal .modal-header {
            border: none !important;
            padding: 1.5rem 1.8rem;
            background: linear-gradient(135deg,
              rgba(15, 23, 42, 0.05) 0%,
              rgba(37, 99, 235, 0.12) 45%,
              rgba(255, 255, 255, 0.92) 100%
            );
            border-bottom: 1px solid rgba(15, 23, 42, 0.08) !important;
          }
        
          #projectDetailsModal .modal-title {
            font-weight: 950;
            font-size: 1.3rem;
            color: #0f172a;
            letter-spacing: -0.6px;
            display: flex;
            align-items: center;
            gap: 10px;
          }
        
          #projectDetailsModal .modal-title::before {
            content: "📌";
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(37,99,235,0.18), rgba(255,255,255,0.85));
            border: 1px solid rgba(37,99,235,0.22);
            box-shadow: 0 10px 22px rgba(15,23,42,0.06);
            font-size: 16px;
          }
        
          /* Body */
          #projectDetailsModal .modal-body {
            padding: 1.6rem 1.8rem 1.8rem 1.8rem;
            color: #334155;
          }
        
          #projectDetailsModal #projectDetailsBody {
            background: rgba(255,255,255,0.85);
            border: 1px solid rgba(15, 23, 42, 0.10);
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
          }
        
          #projectDetailsModal hr {
            border: none;
            height: 1px;
            background: linear-gradient(90deg,
              rgba(15, 23, 42, 0.12),
              rgba(15, 23, 42, 0.05),
              rgba(15, 23, 42, 0.02)
            );
          }
        
          #projectDetailsModal h6 {
            font-weight: 950;
            font-size: 1rem;
            color: #0f172a;
            letter-spacing: -0.4px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
          }
        
          #projectDetailsModal h6::before {
            content: "👨‍💼";
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(37,99,235,0.14), rgba(255,255,255,0.85));
            border: 1px solid rgba(37,99,235,0.20);
            box-shadow: 0 10px 22px rgba(15,23,42,0.06);
            font-size: 14px;
          }
        
          #projectDetailsModal #projectApplicationsBody {
            margin-top: 10px;
          }
        
          /* Footer */
          #projectDetailsModal .modal-footer {
            border: none !important;
            padding: 1.2rem 1.8rem 1.6rem 1.8rem;
            background: rgba(255,255,255,0.70);
            border-top: 1px solid rgba(15, 23, 42, 0.08) !important;
          }
        
          /* Close Button */
          #projectDetailsModal .btn-secondary {
            border-radius: 14px !important;
            padding: 0.75rem 1.4rem !important;
            font-weight: 900 !important;
            background: rgba(15, 23, 42, 0.06) !important;
            border: 1px solid rgba(15, 23, 42, 0.18) !important;
            color: #0f172a !important;
            transition: 0.2s ease;
          }
        
          #projectDetailsModal .btn-secondary:hover {
            transform: translateY(-2px);
            background: rgba(15, 23, 42, 0.10) !important;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
          }
        
          /* Smooth animation */
          #projectDetailsModal.modal.fade .modal-dialog {
            transform: translateY(18px) scale(0.98);
            transition: transform 0.25s ease;
          }
        
          #projectDetailsModal.modal.show .modal-dialog {
            transform: translateY(0px) scale(1);
          }
        
          /* Scrollbar Premium */
          #projectDetailsModal .modal-body::-webkit-scrollbar {
            width: 10px;
          }
        
          #projectDetailsModal .modal-body::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.05);
            border-radius: 12px;
          }
        
          #projectDetailsModal .modal-body::-webkit-scrollbar-thumb {
            background: rgba(37, 99, 235, 0.35);
            border-radius: 12px;
          }
        
          #projectDetailsModal .modal-body::-webkit-scrollbar-thumb:hover {
            background: rgba(37, 99, 235, 0.55);
          }
        
          @media(max-width: 768px) {
            #projectDetailsModal .modal-header,
            #projectDetailsModal .modal-body,
            #projectDetailsModal .modal-footer {
              padding-left: 1.2rem;
              padding-right: 1.2rem;
            }
          }
        </style>
        
        <div class="modal fade" id="projectDetailsModal" tabindex="-1"
             aria-labelledby="projectDetailsLabel" aria-hidden="true">
        
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-4">
        
              <div class="modal-header border-0">
                <h5 class="modal-title" id="projectDetailsLabel">Project Details</h5>
        
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
              </div>
        
              <div class="modal-body pt-0">
                <div id="projectDetailsBody" class="mb-4"></div>
        
                <hr class="my-3">
        
                <h6 class="fw-semibold mb-2">Consultant Applications</h6>
                <div id="projectApplicationsBody"></div>
              </div>
        
              <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill"
                        data-bs-dismiss="modal">
                  <i class="fa-solid fa-xmark me-2"></i> Close
                </button>
              </div>
        
            </div>
          </div>
        </div>
    `;

  document.body.insertAdjacentHTML('beforeend', html);
}

function openProjectDetails(projectId) {
  ensureProjectDetailsModal();

  const pid = String(projectId);
  const p   = projectsById[pid];

  if (!p) {
    console.warn("[projects] Project not found in cache");
    alert("Project not found. Please reload the page.");
    return;
  }

  const title      = escapeHtml(p.title || 'Untitled Project');
  const posted     = formatDateShort(p.posted_at || p.created_at);
  const expiry     = p.expiry_date ? formatDateShort(p.expiry_date) : 'N/A';
  const poster     = escapeHtml(p.poster_name || p.username || 'You');
  const budget     = escapeHtml(p.budget || 'N/A');
  const billing    = escapeHtml(p.billing_cycle || '-');
  const shortDesc  = escapeHtml(p.short_description || '');
  const fullDesc   = escapeHtml(p.description || '');
  const links      = (p.links || '').trim();

  let linksHtml = '';
  if (links) {
    linksHtml = `
      <div class="mb-2">
        <h6 class="fw-semibold mb-1">Reference Links</h6>
        <p class="small mb-0">${escapeHtml(links)}</p>
      </div>`;
  }

  const detailsHtml = `
    <div class="mb-2">
      <h5 class="mb-1">${title}</h5>
      <p class="text-muted small mb-1">
        Posted by <strong>${poster}</strong> on ${posted}
      </p>
      <p class="text-muted small mb-0">
        <strong>Ends:</strong> ${expiry}
      </p>
    </div>

    <div class="row g-2 mb-3 small">
      <div class="col-md-4">
        <div class="border rounded-3 p-2 h-100">
          <div class="text-muted">Budget</div>
          <div class="fw-semibold">${budget}</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="border rounded-3 p-2 h-100">
          <div class="text-muted">Billing</div>
          <div class="fw-semibold">${billing || '-'}</div>
        </div>
      </div>
    </div>

    ${shortDesc ? `
      <div class="mb-2">
        <h6 class="fw-semibold mb-1">Summary</h6>
        <p class="mb-0 small">${shortDesc}</p>
      </div>` : ''}

    <div class="mb-2">
      <h6 class="fw-semibold mb-1">Full Description</h6>
      <p class="mb-0 small">${fullDesc || 'No detailed description provided.'}</p>
    </div>

    ${linksHtml}
  `;

  const detailsBody = document.getElementById('projectDetailsBody');
  if (detailsBody) detailsBody.innerHTML = detailsHtml;

  const label = document.getElementById('projectDetailsLabel');
  if (label) label.textContent = title;

  loadProjectApplications(pid);

  const modalEl = document.getElementById('projectDetailsModal');
  const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
  modal.show();
}

/* ---------- Load Applications + Consultant Details ---------- */
function loadProjectApplications(projectId) {
  const target = document.getElementById('projectApplicationsBody');
  if (!target) return;

  if (!projectId) {
    console.error('[projects] loadProjectApplications called WITHOUT projectId');
    target.innerHTML = `<p class="text-danger small mb-0">No project selected.</p>`;
    return;
  }

  console.log('[projects] Loading applications for project_id =', projectId);

  /* ===============================
     CONSULTME PREMIUM UI CSS
  =============================== */
  if (!document.getElementById("consultme-app-ui-style")) {
    const style = document.createElement("style");
    style.id = "consultme-app-ui-style";
    style.innerHTML = `
      /* Wrapper text */
      #projectApplicationsBody {
        padding-top: 6px;
      }

      /* Application Card */
      .consultme-app-card {
        border-radius: 20px !important;
        border: 1px solid rgba(15, 23, 42, 0.10) !important;
        background: rgba(255, 255, 255, 0.90) !important;
        backdrop-filter: blur(14px);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08) !important;
        overflow: hidden;
        transition: 0.28s ease;
        position: relative;
      }

      .consultme-app-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 28px 70px rgba(15, 23, 42, 0.14) !important;
        border-color: rgba(37, 99, 235, 0.30) !important;
      }

      /* Glow effect */
      .consultme-app-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
          radial-gradient(circle at top left, rgba(37, 99, 235, 0.16), transparent 55%),
          radial-gradient(circle at bottom right, rgba(15, 23, 42, 0.08), transparent 65%);
        opacity: 0.85;
        pointer-events: none;
        z-index: 0;
      }

      .consultme-app-card * {
        position: relative;
        z-index: 2;
      }

      /* Header Strip */
      .consultme-app-topbar {
        height: 5px;
        width: 100%;
        background: linear-gradient(90deg, #0d6efd, #2563eb, #0ea5e9);
      }

      /* Avatar */
      .consultme-avatar {
        width: 68px !important;
        height: 68px !important;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.95) !important;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.18);
        transition: 0.25s ease;
      }

      .consultme-app-card:hover .consultme-avatar {
        transform: scale(1.05);
      }

      /* Name + Username */
      .consultme-name {
        font-weight: 950;
        color: #0f172a;
        font-size: 1.02rem;
        letter-spacing: -0.4px;
      }

      .consultme-username {
        font-weight: 800;
        font-size: 0.88rem;
        color: #64748b;
        margin-left: 6px;
      }

      /* Meta line */
      .consultme-meta {
        font-size: 0.88rem;
        font-weight: 700;
        color: #475569;
        margin-top: 3px;
      }

      /* Badge pills */
      .consultme-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 900;
        color: #0f172a;
        background: rgba(255,255,255,0.88);
        border: 1px solid rgba(15, 23, 42, 0.10);
        box-shadow: 0 10px 22px rgba(15,23,42,0.06);
        white-space: nowrap;
      }

      .consultme-pill i {
        color: #2563eb;
      }

      /* Applied date */
      .consultme-date {
        font-size: 0.82rem;
        font-weight: 800;
        color: #64748b;
        background: rgba(15, 23, 42, 0.04);
        padding: 7px 12px;
        border-radius: 50px;
        border: 1px solid rgba(15, 23, 42, 0.08);
      }

      /* Message box */
      .consultme-msg-box {
        margin-top: 12px;
        padding: 14px 14px;
        border-radius: 16px;
        background: rgba(15, 23, 42, 0.03);
        border: 1px solid rgba(15, 23, 42, 0.08);
      }

      .consultme-msg-title {
        font-weight: 950;
        font-size: 0.9rem;
        color: #0f172a;
        margin-bottom: 4px;
      }

      .consultme-msg-text {
        font-size: 0.9rem;
        font-weight: 600;
        color: #334155;
        line-height: 1.7;
      }

      /* Portfolio link */
      .consultme-portfolio a {
        font-weight: 900;
        text-decoration: none;
        color: #2563eb;
      }

      .consultme-portfolio a:hover {
        text-decoration: underline;
      }

      /* Premium Button */
      .consultme-btn {
        border-radius: 14px !important;
        font-weight: 900 !important;
        padding: 0.55rem 1rem !important;
        border: 1px solid rgba(37, 99, 235, 0.28) !important;
        color: #2563eb !important;
        background: rgba(255, 255, 255, 0.85) !important;
        transition: 0.22s ease;
      }

      .consultme-btn:hover {
        transform: translateY(-2px);
        background: linear-gradient(90deg, #0d6efd, #2563eb, #0ea5e9) !important;
        color: white !important;
        border-color: transparent !important;
        box-shadow: 0 18px 45px rgba(37, 99, 235, 0.25);
      }

      /* Mobile */
      @media(max-width: 576px) {
        .consultme-avatar {
          width: 58px !important;
          height: 58px !important;
        }
      }
    `;
    document.head.appendChild(style);
  }

  target.innerHTML = `<p class="text-muted small">Loading applications...</p>`;

  fetch(`${PROJECT_APPS}?project_id=${encodeURIComponent(projectId)}`)
    .then(res => res.text())
    .then(raw => {
      try {
        const json = JSON.parse(raw);
        return json;
      } catch (e) {
        console.error("[projects] Invalid JSON from fetch_project_applications.php:");
        console.log(raw);
        throw new Error("Invalid JSON response from server.");
      }
    })
    .then(json => {
      if (!json.success) {
        target.innerHTML =
          `<p class="text-danger small">${escapeHtml(json.message || 'Failed to load applications.')}</p>`;
        return;
      }

      const apps = json.applications || [];
      currentProjectApplications = apps; // store for View Profile

      if (apps.length === 0) {
        target.innerHTML = `<p class="text-muted small mb-0">No consultants have applied yet.</p>`;
        return;
      }

      const cardsHtml = apps.map(a => {
        const uname    = escapeHtml(a.username || '');
        const full     = escapeHtml(a.full_name || a.consultant_name || a.username || '');
        const state    = escapeHtml(a.state || '');
        const identity = escapeHtml(a.identity || '');
        const expert   = escapeHtml(a.area_of_expertise || '');
        const msg      = escapeHtml(a.user_msg || '');
        const link     = escapeHtml(a.portfolio_link || '');
        const when     = a.applied_at ? formatDateShort(a.applied_at) : '';
        let avatar     = escapeHtml(a.profile_img || '');

        if (!avatar) {
          avatar = '/uploads/default.png';
        } else if (!avatar.startsWith('http') && !avatar.startsWith('./') && !avatar.startsWith('/')) {
          avatar = '/uploads/consultants/' + avatar;
        }

        return `
          <div class="card consultme-app-card mb-3" data-username="${uname}">
            <div class="consultme-app-topbar"></div>

            <div class="card-body d-flex gap-3 p-4">

              <div class="flex-shrink-0">
                <img src="${avatar}"
                     alt="${full || uname}"
                     class="consultme-avatar">
              </div>

              <div class="flex-grow-1">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                  <div>
                    <div class="consultme-name">
                      ${full || uname}
                      ${uname ? `<span class="consultme-username">@${uname}</span>` : ''}
                    </div>

                    <div class="consultme-meta">
                      ${identity || 'Consultant'}
                      ${expert ? ` • ${expert}` : ''}
                    </div>

                    ${state ? `
                      <div class="mt-2">
                        <span class="consultme-pill">
                          <i class="fa-solid fa-location-dot"></i> ${state}
                        </span>
                      </div>` : ''}
                  </div>

                  ${when ? `<div class="consultme-date"><i class="fa-regular fa-calendar me-1"></i>${when}</div>` : ''}
                </div>

                <div class="consultme-msg-box">
                  <div class="consultme-msg-title">
                    <i class="fa-solid fa-message text-primary me-2"></i>Message
                  </div>
                  <div class="consultme-msg-text">
                    ${msg || '<span class="text-muted fw-bold">No message provided.</span>'}
                  </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                  <div class="small consultme-portfolio">
                    <span class="text-muted fw-bold">Portfolio:</span>
                    ${
                      link
                        ? `<a href="${link}" target="_blank" rel="noopener noreferrer">
                            <i class="fa-solid fa-link me-1"></i> View link
                          </a>`
                        : `<span class="text-muted fw-bold">N/A</span>`
                    }
                  </div>

                  <button type="button"
                          class="btn btn-sm consultme-btn"
                          onclick="viewConsultantProfile('${uname}')">
                    <i class="fa-solid fa-user me-2"></i>View Profile
                  </button>
                </div>

              </div>

            </div>
          </div>
        `;
      }).join('');

      target.innerHTML = cardsHtml;
    })
    .catch(err => {
      console.error("[projects] Applications fetch error:", err);
      target.innerHTML =
        `<p class="text-danger small mb-0">Failed to load applications: ${escapeHtml(err.message)}</p>`;
    });
}

/* ---------- Consultant Profile Modal (from applications) ---------- */
let consultantProfileModalInit = false;

function ensureConsultantProfileModal() {
  if (consultantProfileModalInit) return;
  consultantProfileModalInit = true;

  const modalHTML = `
        <style>
          /* ===============================
             CONSULTME PREMIUM PROFILE MODAL
          =============================== */
        
          #consultantProfileModal .modal-dialog {
            max-width: 980px;
          }
        
          #consultantProfileModal .modal-content {
            border-radius: 22px !important;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(18px);
            box-shadow: 0 40px 110px rgba(15, 23, 42, 0.22);
            overflow: hidden;
          }
        
          /* Header */
          #consultantProfileModal .modal-header {
            border: none !important;
            padding: 1.5rem 1.8rem;
            background: linear-gradient(135deg,
              rgba(15, 23, 42, 0.05) 0%,
              rgba(37, 99, 235, 0.12) 45%,
              rgba(255, 255, 255, 0.92) 100%
            );
            border-bottom: 1px solid rgba(15, 23, 42, 0.08) !important;
          }
        
          #consultantProfileModal .modal-title {
            font-weight: 950;
            font-size: 1.3rem;
            color: #0f172a;
            letter-spacing: -0.6px;
            display: flex;
            align-items: center;
            gap: 10px;
          }
        
          #consultantProfileModal .modal-title::before {
            content: "👤";
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(37,99,235,0.18), rgba(255,255,255,0.85));
            border: 1px solid rgba(37,99,235,0.22);
            box-shadow: 0 10px 22px rgba(15,23,42,0.06);
            font-size: 16px;
          }
        
          /* Body */
          #consultantProfileModal .modal-body {
            padding: 1.6rem 1.8rem 1.8rem 1.8rem;
            color: #334155;
          }
        
          #consultantProfileModal #consultantProfileBody {
            background: rgba(255,255,255,0.86);
            border: 1px solid rgba(15, 23, 42, 0.10);
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
          }
        
          /* Footer */
          #consultantProfileModal .modal-footer {
            border: none !important;
            padding: 1.2rem 1.8rem 1.6rem 1.8rem;
            background: rgba(255,255,255,0.70);
            border-top: 1px solid rgba(15, 23, 42, 0.08) !important;
          }
        
          /* Close Button */
          #consultantProfileModal .btn-secondary {
            border-radius: 14px !important;
            padding: 0.75rem 1.4rem !important;
            font-weight: 900 !important;
            background: rgba(15, 23, 42, 0.06) !important;
            border: 1px solid rgba(15, 23, 42, 0.18) !important;
            color: #0f172a !important;
            transition: 0.2s ease;
          }
        
          #consultantProfileModal .btn-secondary:hover {
            transform: translateY(-2px);
            background: rgba(15, 23, 42, 0.10) !important;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
          }
        
          /* Smooth modal animation */
          #consultantProfileModal.modal.fade .modal-dialog {
            transform: translateY(18px) scale(0.98);
            transition: transform 0.25s ease;
          }
        
          #consultantProfileModal.modal.show .modal-dialog {
            transform: translateY(0px) scale(1);
          }
        
          /* Scrollbar Premium */
          #consultantProfileModal .modal-body::-webkit-scrollbar {
            width: 10px;
          }
        
          #consultantProfileModal .modal-body::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.05);
            border-radius: 12px;
          }
        
          #consultantProfileModal .modal-body::-webkit-scrollbar-thumb {
            background: rgba(37, 99, 235, 0.35);
            border-radius: 12px;
          }
        
          #consultantProfileModal .modal-body::-webkit-scrollbar-thumb:hover {
            background: rgba(37, 99, 235, 0.55);
          }
        
          @media(max-width: 768px) {
            #consultantProfileModal .modal-header,
            #consultantProfileModal .modal-body,
            #consultantProfileModal .modal-footer {
              padding-left: 1.2rem;
              padding-right: 1.2rem;
            }
          }
        </style>
        
        <div class="modal fade" id="consultantProfileModal" tabindex="-1">
          <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4">
        
              <div class="modal-header border-0">
                <h5 class="modal-title">Consultant Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
        
              <div class="modal-body pt-0">
                <div id="consultantProfileBody">
                  <p class="text-muted fw-semibold mb-0">Loading...</p>
                </div>
              </div>
        
              <div class="modal-footer border-0">
                <button class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                  <i class="fa-solid fa-xmark me-2"></i> Close
                </button>
              </div>
        
            </div>
          </div>
        </div>
    `;

  document.body.insertAdjacentHTML("beforeend", modalHTML);
}

function viewConsultantProfile(username) {
  ensureConsultantProfileModal();

  const modalEl = document.getElementById("consultantProfileModal");
  const bodyEl  = document.getElementById("consultantProfileBody");

  bodyEl.innerHTML = `<p class="text-muted">Loading profile...</p>`;

  const apps = currentProjectApplications || [];
  const profileData = apps.find(a => a.username === username);

  if (!profileData) {
    bodyEl.innerHTML = `<p class="text-danger">Profile not found.</p>`;
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
    return;
  }

  const avatar = profileData.profile_img
    ? ((profileData.profile_img.startsWith('http') || profileData.profile_img.startsWith('./') || profileData.profile_img.startsWith('/'))
        ? profileData.profile_img
        : `/uploads/consultants/${profileData.profile_img}`)
    : "/uploads/default.png";

  const fullName   = escapeHtml(profileData.full_name || profileData.name || profileData.username || '');
  const uname      = escapeHtml(profileData.username || '');
  const identity   = escapeHtml(profileData.identity || "Consultant");
  const expertise  = escapeHtml(profileData.area_of_expertise || "N/A");
  const experience = escapeHtml(profileData.experience || "N/A");
  const rate       = escapeHtml(profileData.hourly_rate != null ? profileData.hourly_rate : "N/A");
  const state      = escapeHtml(profileData.state || "N/A");
  const phone      = escapeHtml(profileData.phone || "N/A");
  const email      = escapeHtml(profileData.email || "N/A");
  const bio        = escapeHtml(profileData.bio || "No bio provided.");

  bodyEl.innerHTML = `
        <style>
          /* ===============================
             CONSULTME PREMIUM PROFILE BODY
          =============================== */
        
          .consultme-profile-wrap{
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(15, 23, 42, 0.10);
            border-radius: 22px;
            padding: 22px;
            box-shadow: 0 22px 60px rgba(15, 23, 42, 0.10);
            overflow: hidden;
            position: relative;
          }
        
          .consultme-profile-wrap::before{
            content: "";
            position: absolute;
            inset: 0;
            background:
              radial-gradient(circle at top left, rgba(37, 99, 235, 0.16), transparent 55%),
              radial-gradient(circle at bottom right, rgba(15, 23, 42, 0.10), transparent 65%);
            opacity: 0.85;
            pointer-events: none;
            z-index: 0;
          }
        
          .consultme-profile-wrap *{
            position: relative;
            z-index: 2;
          }
        
          .consultme-avatar{
            width: 118px;
            height: 118px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255,255,255,0.95);
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.18);
            transition: 0.25s ease;
          }
        
          .consultme-avatar:hover{
            transform: scale(1.05);
          }
        
          .consultme-name{
            font-weight: 950;
            font-size: 1.5rem;
            color: #0f172a;
            letter-spacing: -0.6px;
            margin-top: 14px;
            margin-bottom: 4px;
          }
        
          .consultme-username{
            font-weight: 800;
            font-size: 0.95rem;
            color: #64748b;
          }
        
          .consultme-badge{
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 50px;
            font-weight: 900;
            font-size: 0.85rem;
            background: linear-gradient(90deg, #0d6efd, #2563eb, #0ea5e9);
            color: #fff;
            box-shadow: 0 18px 45px rgba(37, 99, 235, 0.22);
            margin-top: 12px;
          }
        
          .consultme-info-grid{
            margin-top: 18px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
          }
        
          .consultme-info-card{
            background: rgba(255,255,255,0.82);
            border: 1px solid rgba(15, 23, 42, 0.10);
            border-radius: 18px;
            padding: 14px 16px;
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
            transition: 0.25s ease;
          }
        
          .consultme-info-card:hover{
            transform: translateY(-3px);
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.10);
            border-color: rgba(37, 99, 235, 0.22);
          }
        
          .consultme-info-title{
            font-weight: 950;
            font-size: 0.85rem;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
          }
        
          .consultme-info-title i{
            color: #2563eb;
          }
        
          .consultme-info-value{
            font-weight: 700;
            font-size: 0.92rem;
            color: #475569;
            line-height: 1.5;
          }
        
          .consultme-divider{
            height: 1px;
            border: none;
            margin: 1.3rem 0;
            background: linear-gradient(90deg,
              rgba(15, 23, 42, 0.12),
              rgba(15, 23, 42, 0.05),
              rgba(15, 23, 42, 0.02)
            );
          }
        
          .consultme-bio-box{
            background: rgba(15, 23, 42, 0.03);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 18px;
            padding: 16px;
          }
        
          .consultme-bio-title{
            font-weight: 950;
            font-size: 1rem;
            color: #0f172a;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
          }
        
          .consultme-bio-title i{
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(37,99,235,0.16), rgba(255,255,255,0.85));
            border: 1px solid rgba(37,99,235,0.22);
            color: #2563eb;
            box-shadow: 0 10px 22px rgba(15,23,42,0.06);
          }
        
          .consultme-bio-text{
            font-weight: 600;
            font-size: 0.95rem;
            color: #334155;
            line-height: 1.75;
            margin-bottom: 0;
          }
        
          @media(max-width: 768px){
            .consultme-info-grid{
              grid-template-columns: 1fr;
            }
          }
        </style>
        
        <div class="consultme-profile-wrap">
        
          <!-- PROFILE HEADER -->
          <div class="text-center">
            <img src="${avatar}" class="consultme-avatar" alt="${fullName}">
        
            <div class="consultme-name">${fullName}</div>
        
            ${uname ? `<div class="consultme-username">@${uname}</div>` : ""}
        
            <div>
              <span class="consultme-badge">
                <i class="fa-solid fa-user-tie"></i> ${identity}
              </span>
            </div>
          </div>
        
          <!-- INFO GRID -->
          <div class="consultme-info-grid">
        
            <div class="consultme-info-card">
              <div class="consultme-info-title"><i class="fa-solid fa-briefcase"></i> Expertise</div>
              <div class="consultme-info-value">${expertise}</div>
            </div>
        
            <div class="consultme-info-card">
              <div class="consultme-info-title"><i class="fa-solid fa-chart-line"></i> Experience</div>
              <div class="consultme-info-value">${experience}</div>
            </div>
        
            <div class="consultme-info-card">
              <div class="consultme-info-title"><i class="fa-solid fa-indian-rupee-sign"></i> Hourly Rate</div>
              <div class="consultme-info-value"><span class="fw-bold text-primary">₹${rate}</span></div>
            </div>
        
            <div class="consultme-info-card">
              <div class="consultme-info-title"><i class="fa-solid fa-location-dot"></i> State</div>
              <div class="consultme-info-value">${state}</div>
            </div>
        
            <div class="consultme-info-card">
              <div class="consultme-info-title"><i class="fa-solid fa-phone"></i> Phone</div>
              <div class="consultme-info-value">${phone}</div>
            </div>
        
            <div class="consultme-info-card">
              <div class="consultme-info-title"><i class="fa-solid fa-envelope"></i> Email</div>
              <div class="consultme-info-value">${email}</div>
            </div>
        
          </div>
        
          <hr class="consultme-divider">
        
          <!-- BIO -->
          <div class="consultme-bio-box">
            <div class="consultme-bio-title">
              <i class="fa-solid fa-circle-info"></i> Bio
            </div>
            <p class="consultme-bio-text">${bio}</p>
          </div>
        
        </div>
    `;


  const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
  modal.show();
}

/* ---------- Post Project Submit ---------- */
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("postProjectForm");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const fd = new FormData(form);

      fetch(PROJECT_CREATE, {
        method: "POST",
        body: fd
      })
        .then(res => res.json())
        .then(json => {
          if (json.success) {
            alert("Project posted successfully!");
            form.reset();
            closePostProjectModal();
            loadProjects("active", 1);
          } else {
            alert("Error: " + (json.message || "Unable to post project."));
          }
        })
        .catch(err => {
          console.error("[projects] Create error:", err);
          alert("Failed to post project.");
        });
    });
  }
});