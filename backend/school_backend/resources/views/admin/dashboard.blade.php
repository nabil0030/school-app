<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Student Applications</title>
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
            --radius: 12px;
            --radius-sm: 8px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: var(--dark);
            min-height: 100vh;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: var(--dark);
            color: white;
            padding: 30px 0;
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-lg);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 25px;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
        }

        .logo-text {
            font-size: 20px;
            font-weight: 700;
            background: linear-gradient(135deg, white, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            flex: 1;
            padding: 0 15px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            margin: 8px 0;
            border-radius: var(--radius-sm);
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 15px;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, var(--primary), rgba(79, 70, 229, 0.2));
            color: white;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .nav-icon {
            font-size: 18px;
            width: 24px;
        }

        .logout-btn {
            margin: 20px 25px;
            padding: 12px;
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 15px;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            transform: translateY(-2px);
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header h1::before {
            content: '';
            width: 4px;
            height: 28px;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 10px 20px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* STATS CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .pending .stat-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .accepted .stat-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .rejected .stat-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .stat-info h3 {
            font-size: 14px;
            color: var(--gray);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-info .number {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
        }

        /* FILTERS */
        .filters-container {
            background: white;
            padding: 20px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 10px 20px;
            background: var(--light);
            border: 2px solid var(--gray-light);
            border-radius: 50px;
            color: var(--gray);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .filter-tab:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .filter-tab.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .search-box {
            position: relative;
            max-width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 14px 20px 14px 45px;
            border: 2px solid var(--gray-light);
            border-radius: 50px;
            font-size: 15px;
            transition: all 0.2s ease;
            background: var(--light);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        /* TABLE */
        .table-container {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(90deg, var(--dark), var(--dark-light));
        }

        th {
            padding: 18px 20px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        th:first-child {
            border-radius: var(--radius) 0 0 0;
        }

        th:last-child {
            border-radius: 0 var(--radius) 0 0;
        }

        tbody tr {
            border-bottom: 1px solid var(--gray-light);
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: var(--light);
        }

        td {
            padding: 18px 20px;
            color: var(--dark);
            font-size: 14px;
        }

        .student-name {
            font-weight: 600;
            color: var(--dark);
        }

        .student-email {
            color: var(--gray);
            font-size: 13px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-accepted {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .action-btn {
            padding: 8px 16px;
            background: var(--primary);
            color: white;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .action-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 20px;
            }
            
            .nav-menu {
                display: flex;
                gap: 10px;
                overflow-x: auto;
                padding-bottom: 10px;
            }
            
            .nav-item {
                white-space: nowrap;
                margin: 0;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            th, td {
                padding: 12px;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            table {
                min-width: 800px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon">A</div>
                <div class="logo-text">AdminHub</div>
            </div>
            
            <div class="nav-menu">
                <a href="/admin/dashboard" class="nav-item active">
                    <span class="nav-icon">📋</span>
                    <span>Applications</span>
                </a>
               
                
            </div>
            
            <a href="/admin/logout" class="logout-btn">
                <span class="nav-icon">🚪</span>
                <span>Logout</span>
            </a>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- HEADER -->
            <div class="header">
                <h1>Student Applications</h1>
                <div class="admin-info">
                    <div class="admin-avatar">A</div>
                    <div>
                        <div style="font-weight: 600;">Administrator</div>
                        <div style="font-size: 12px; color: var(--gray);">School Management System</div>
                    </div>
                </div>
            </div>

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Pending</h3>
                        <div class="number">{{ $stats['pending'] }}</div>
                    </div>
                </div>
                
                <div class="stat-card accepted">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Accepted</h3>
                        <div class="number">{{ $stats['accepted'] }}</div>
                    </div>
                </div>
                
                <div class="stat-card rejected">
                    <div class="stat-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Rejected</h3>
                        <div class="number">{{ $stats['rejected'] }}</div>
                    </div>
                </div>
            </div>

            <!-- FILTERS & SEARCH -->
            <div class="filters-container">
                <div class="filter-tabs">
                    <a href="/admin/dashboard" 
                       class="filter-tab {{ empty($status) ? 'active' : '' }}">
                        All Applications
                    </a>
                    <a href="/admin/dashboard?status=pending" 
                       class="filter-tab {{ $status === 'pending' ? 'active' : '' }}">
                        Pending
                    </a>
                    <a href="/admin/dashboard?status=accepted" 
                       class="filter-tab {{ $status === 'accepted' ? 'active' : '' }}">
                        Accepted
                    </a>
                    <a href="/admin/dashboard?status=rejected" 
                       class="filter-tab {{ $status === 'rejected' ? 'active' : '' }}">
                        Rejected
                    </a>
                </div>
                
                <form method="GET" action="/admin/dashboard" class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           name="search" 
                           placeholder="Search students by name, CIN, or email"
                           value="{{ $search ?? '' }}">
                    
                    @if($status)
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                </form>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                @if($students->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Email</th>
                                <th>CIN</th>
                                <th>BAC</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>#{{ $student->id }}</td>
                                <td>
                                    <div class="student-name">{{ $student->nom }} {{ $student->prenom }}</div>
                                    <div class="student-email">{{ $student->email }}</div>
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->cin ?? '—' }}</td>
                                <td>{{ $student->bac_number ?? '—' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $student->status }}">
                                        {{ strtoupper($student->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/students/{{ $student->id }}" class="action-btn">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <h3 style="margin-bottom: 10px; color: var(--dark);">No applications found</h3>
                        <p style="color: var(--gray); max-width: 300px; margin: 0 auto;">
                            {{ $search ? 'No results match your search criteria.' : 'There are no student applications at the moment.' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Auto-submit search on typing (with debounce)
        let searchTimeout;
        document.querySelector('input[name="search"]').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                e.target.closest('form').submit();
            }, 500);
        });

        // Add smooth scrolling for table on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const tableContainer = document.querySelector('.table-container');
            if (window.innerWidth < 640) {
                tableContainer.style.overflowX = 'auto';
            }
        });

        // Add loading state to filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                if (!this.classList.contains('active')) {
                    this.style.opacity = '0.7';
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                }
            });
        });
    </script>
</body>
</html>