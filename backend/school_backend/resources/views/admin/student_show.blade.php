<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $student->nom }} {{ $student->prenom }} | Student Details</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1e293b;
            --dark-light: #334155;
            --light: #f8fafc;
            --gray: #64748b;
            --gray-light: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius: 16px;
            --radius-sm: 10px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: var(--dark);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-light);
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            border: 2px solid var(--gray-light);
            border-radius: var(--radius-sm);
            color: var(--gray);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .back-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateX(-3px);
        }

        .student-header {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .student-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .student-info h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .student-status {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-accepted {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* MAIN LAYOUT */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }

        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        /* CARDS */
        .card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--gray-light);
        }

        .card-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }

        /* INFO GRID */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-item {
            padding: 12px;
            background: var(--light);
            border-radius: var(--radius-sm);
            border-left: 4px solid var(--primary);
        }

        .info-label {
            font-size: 12px;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .info-value {
            font-size: 16px;
            color: var(--dark);
            font-weight: 500;
        }

        /* DOCUMENTS */
        .documents-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            .documents-grid {
                grid-template-columns: 1fr;
            }
        }

        .document-card {
            border: 2px solid var(--gray-light);
            border-radius: var(--radius-sm);
            padding: 15px;
            transition: all 0.2s ease;
        }

        .document-card:hover {
            border-color: var(--primary);
        }

        .document-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .document-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            background: var(--light);
            border: 1px solid var(--gray-light);
        }

        .no-document {
            padding: 30px;
            text-align: center;
            background: var(--light);
            border-radius: 8px;
            color: var(--gray);
        }

        .no-document-icon {
            font-size: 32px;
            margin-bottom: 10px;
            opacity: 0.3;
        }

        /* DECISION CARD */
        .decision-card {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 2px solid var(--gray-light);
        }

        .decision-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .decision-header .card-icon {
            margin: 0 auto 15px;
        }

        .decision-buttons {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        @media (max-width: 640px) {
            .decision-buttons {
                flex-direction: column;
            }
        }

        .btn {
            flex: 1;
            padding: 16px 24px;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        .btn-accept {
            background: linear-gradient(135deg, var(--secondary), #059669);
            color: white;
        }

        .btn-reject {
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: white;
        }

        .btn-icon {
            font-size: 18px;
        }

        /* COMMENT FORM */
        .comment-form {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid var(--gray-light);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        textarea {
            width: 100%;
            padding: 16px;
            border: 2px solid var(--gray-light);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 14px;
            color: var(--dark);
            background: white;
            transition: all 0.2s ease;
            resize: vertical;
            min-height: 100px;
        }

        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* CURRENT STATUS */
        .current-status {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 25px;
            border-radius: var(--radius);
            margin-top: 25px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
        }

        /* LOADING STATES */
        .loading {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <a href="/admin/dashboard" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
            
            <div class="student-header">
                <div class="student-avatar">
                    {{ substr($student->nom, 0, 1) }}{{ substr($student->prenom, 0, 1) }}
                </div>
                <div class="student-info">
                    <h1>{{ $student->nom }} {{ $student->prenom }}</h1>
                    <div class="student-status status-{{ $student->status }}">
                        Status: {{ strtoupper($student->status) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- CURRENT STATUS -->
        @if($student->admin_comment)
        <div class="current-status">
            <h3 style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-comment"></i> Admin Comment
            </h3>
            <div style="background: rgba(255, 255, 255, 0.1); padding: 15px; border-radius: 10px;">
                <p style="margin: 0; font-style: italic;">"{{ $student->admin_comment }}"</p>
                <div style="font-size: 12px; opacity: 0.8; margin-top: 10px;">
                    Added {{ $student->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @endif

        <!-- MAIN GRID -->
        <div class="main-grid">
            <!-- PERSONAL INFO -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="card-title">Personal Information</h3>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $student->nom }} {{ $student->prenom }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email Address</div>
                        <div class="info-value">{{ $student->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">CIN Number</div>
                        <div class="info-value">{{ $student->cin ?? 'Not provided' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Application ID</div>
                        <div class="info-value">#{{ $student->id }}</div>
                    </div>
                </div>
            </div>

            <!-- ACADEMIC INFO -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="card-title">Academic Information</h3>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">BAC Number</div>
                        <div class="info-value">{{ $student->bac_number ?? 'Not provided' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">BAC Year</div>
                        <div class="info-value">{{ $student->bac_year ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Mention</div>
                        <div class="info-value">{{ $student->bac_mention ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Application Date</div>
                        <div class="info-value">{{ $student->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DOCUMENTS -->
        <div class="card" style="margin-bottom: 25px;">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="card-title">Submitted Documents</h3>
            </div>
            <div class="documents-grid">
                <!-- CIN Document -->
                <div class="document-card">
                    <div class="document-title">
                        <i class="fas fa-id-card"></i>
                        Carte Nationale d'Identité
                    </div>
                    @if($student->cin_image ?? false)
                        <img src="{{ asset('storage/' . $student->cin_image) }}" 
                             alt="CIN Document" 
                             class="document-image"
                             onclick="openModal(this.src)">
                        <div style="text-align: center; margin-top: 10px;">
                            <button onclick="openModal('{{ asset('storage/' . $student->cin_image) }}')" 
                                    style="padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-expand"></i> View Full Size
                            </button>
                        </div>
                    @else
                        <div class="no-document">
                            <div class="no-document-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <p>No CIN image uploaded</p>
                        </div>
                    @endif
                </div>

                <!-- BAC Document -->
                <div class="document-card">
                    <div class="document-title">
                        <i class="fas fa-certificate"></i>
                        Diplôme de Baccalauréat
                    </div>
                    @if($student->bac_image)
                        <img src="{{ asset('storage/' . $student->bac_image) }}" 
                             alt="BAC Document" 
                             class="document-image"
                             onclick="openModal(this.src)">
                        <div style="text-align: center; margin-top: 10px;">
                            <button onclick="openModal('{{ asset('storage/' . $student->bac_image) }}')" 
                                    style="padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-expand"></i> View Full Size
                            </button>
                        </div>
                    @else
                        <div class="no-document">
                            <div class="no-document-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <p>No BAC image uploaded</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ADMIN DECISION -->
        <div class="card decision-card">
            <div class="decision-header">
                <div class="card-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <h3 class="card-title">Review & Decision</h3>
                <p style="color: var(--gray); margin-top: 5px;">Make your decision after reviewing all documents</p>
            </div>

            <!-- Accept Form -->
            <form method="POST" action="/admin/students/{{ $student->id }}/accept" 
                  onsubmit="showLoading(this, 'accept')">
                @csrf
                <div class="decision-buttons">
                    <button type="submit" class="btn btn-accept" id="acceptBtn">
                        <i class="fas fa-check btn-icon"></i>
                        <span>Accept Application</span>
                        <div class="loading" id="acceptLoading"></div>
                    </button>
            </form>

            <!-- Reject Form -->
            <form method="POST" action="/admin/students/{{ $student->id }}/reject" 
                  onsubmit="return validateRejectForm(this)">
                @csrf
                    <button type="button" class="btn btn-reject" onclick="toggleRejectForm()" id="rejectBtn">
                        <i class="fas fa-times btn-icon"></i>
                        <span>Reject Application</span>
                    </button>
                </div>

                <div class="comment-form" id="rejectForm" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-comment-dots"></i> Reason for Rejection (Optional)
                        </label>
                        <textarea name="comment" 
                                  placeholder="Please provide a reason for rejection. This will be visible to the student..."
                                  rows="4">{{ $student->admin_comment ?? '' }}</textarea>
                    </div>
                    <div class="decision-buttons">
                        <button type="submit" class="btn btn-reject" id="confirmRejectBtn">
                            <i class="fas fa-ban btn-icon"></i>
                            <span>Confirm Rejection</span>
                            <div class="loading" id="rejectLoading"></div>
                        </button>
                        <button type="button" class="btn" onclick="toggleRejectForm()" 
                                style="background: var(--gray-light); color: var(--dark);">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL FOR IMAGES -->
    <div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 1000; justify-content: center; align-items: center;">
        <div style="position: relative; max-width: 90%; max-height: 90%;">
            <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 90vh; border-radius: 8px;">
            <button onclick="closeModal()" 
                    style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 20px;">
                ×
            </button>
        </div>
    </div>

    <script>
        // Image Modal
        function openModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        // Reject Form Toggle
        function toggleRejectForm() {
            const form = document.getElementById('rejectForm');
            const rejectBtn = document.getElementById('rejectBtn');
            
            if (form.style.display === 'none') {
                form.style.display = 'block';
                rejectBtn.style.display = 'none';
                rejectBtn.parentElement.style.marginBottom = '0';
            } else {
                form.style.display = 'none';
                rejectBtn.style.display = 'flex';
            }
        }

        // Form Validation
        function validateRejectForm(form) {
            if (confirm('Are you sure you want to reject this application? This action cannot be undone.')) {
                showLoading(form, 'reject');
                return true;
            }
            return false;
        }

        // Loading States
        function showLoading(form, type) {
            const loadingEl = document.getElementById(type + 'Loading');
            const button = document.querySelector('#' + type + 'Btn');
            const text = button.querySelector('span');
            
            text.style.display = 'none';
            loadingEl.style.display = 'block';
            button.disabled = true;
            
            // Add slight delay for better UX
            setTimeout(() => {
                form.submit();
            }, 500);
        }

        // Auto-focus comment textarea when rejecting
        document.addEventListener('DOMContentLoaded', function() {
            const rejectForm = document.getElementById('rejectForm');
            if (rejectForm.style.display === 'block') {
                rejectForm.querySelector('textarea').focus();
            }
        });
    </script>
</body>
</html>