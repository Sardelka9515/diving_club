@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">活動詳情</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> 返回列表
            </a>
            <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> 編輯
            </a>
            <form method="POST" action="{{ route('admin.activities.destroy', $activity) }}" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('確定要刪除此活動嗎？')">
                    <i class="bi bi-trash"></i> 刪除
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $activity->title }}
                        @if ($activity->is_published)
                            <span class="badge bg-success ms-2">已發布</span>
                        @else
                            <span class="badge bg-warning ms-2">草稿</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="text-muted">活動描述</h6>
                    <p class="mb-3">{{ $activity->description }}</p>

                    <h6 class="text-muted">活動內容</h6>
                    <div class="border rounded p-3 bg-light">
                        {!! $activity->content !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">活動資訊</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-5"><strong>類別：</strong></div>
                        <div class="col-sm-7">
                            @if ($activity->category)
                                <span class="badge bg-secondary">{{ $activity->category->name }}</span>
                            @else
                                <span class="text-muted">未分類</span>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-5"><strong>地點：</strong></div>
                        <div class="col-sm-7">{{ $activity->location }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-5"><strong>費用：</strong></div>
                        <div class="col-sm-7">
                            <span class="text-success fw-bold">NT$ {{ number_format($activity->price) }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-5"><strong>人數限制：</strong></div>
                        <div class="col-sm-7">
                            @if ($activity->max_participants > 0)
                                {{ $activity->max_participants }} 人
                            @else
                                <span class="text-muted">無限制</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">時間資訊</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">活動時間</small>
                        <div class="fw-bold">
                            {{ $activity->start_date->format('Y年m月d日 H:i') }}
                            <br>
                            至 {{ $activity->end_date->format('Y年m月d日 H:i') }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">報名時間</small>
                        <div>
                            {{ $activity->registration_start->format('Y年m月d日 H:i') }}
                            <br>
                            至 {{ $activity->registration_end->format('Y年m月d日 H:i') }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">創建時間</small>
                        <div>{{ $activity->created_at->format('Y年m月d日 H:i') }}</div>
                    </div>
                    <div>
                        <small class="text-muted">最後更新</small>
                        <div>{{ $activity->updated_at->format('Y年m月d日 H:i') }}</div>
                    </div>
                </div>
            </div>

            @if ($activity->registrations->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">報名狀況</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>總報名人數</span>
                                <span class="fw-bold">{{ $activity->registrations->count() }}</span>
                            </div>
                            @if ($activity->max_participants > 0)
                                <div class="progress mt-2">
                                    @php
                                        $percentage = min(
                                            100,
                                            ($activity->registrations->count() / $activity->max_participants) * 100,
                                        );
                                    @endphp
                                    <div class="progress-bar bg-{{ $percentage >= 100 ? 'danger' : ($percentage >= 80 ? 'warning' : 'success') }}"
                                        role="progressbar" style="width: {{ $percentage }}%">
                                        {{ round($percentage) }}%
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row text-center">
                            <div class="col">
                                <div class="text-success fw-bold">
                                    {{ $activity->registrations->where('status', 'approved')->count() }}</div>
                                <small class="text-muted">已確認</small>
                            </div>
                            <div class="col">
                                <div class="text-warning fw-bold">
                                    {{ $activity->registrations->where('status', 'pending')->count() }}</div>
                                <small class="text-muted">待審核</small>
                            </div>
                            <div class="col">
                                <div class="text-danger fw-bold">
                                    {{ $activity->registrations->where('status', 'rejected')->count() }}</div>
                                <small class="text-muted">已拒絕</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">報名使用者列表</h5>
                    <div>
                        <span class="badge bg-primary me-2">總計: {{ $activity->registrations->count() }} 人</span>
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal"
                            data-bs-target="#addRegistrationModal">
                            <i class="bi bi-person-plus me-1"></i> 新增報名
                        </button>
                        @if ($activity->registrations->count() > 0)
                            <form action="{{ route('admin.registrations.export') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-file-excel me-1"></i> 匯出名單
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if ($activity->registrations->count() > 0)
                        @if ($activity->registrations->where('status', 'pending')->count() > 0)
                            <div class="mb-3">
                                <form id="bulkActionForm" class="d-flex align-items-center">
                                    @csrf
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label" for="selectAll">全選</label>
                                    </div>
                                    <div class="btn-group me-2">
                                        <button type="button" class="btn btn-sm btn-outline-success bulk-approve">
                                            <i class="bi bi-check2-all"></i> 批量核准
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger bulk-reject">
                                            <i class="bi bi-x-lg"></i> 批量拒絕
                                        </button>
                                    </div>
                                    <span class="text-muted small">已選擇 <span id="selectedCount">0</span> 筆</span>
                                </form>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>姓名</th>
                                        <th>電子郵件</th>
                                        <th>電話</th>
                                        <th>報名時間</th>
                                        <th>狀態</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activity->registrations as $index => $registration)
                                        <tr>
                                            <td>
                                                @if ($registration->status == 'pending')
                                                    <input type="checkbox" name="registration_ids[]"
                                                        value="{{ $registration->id }}" class="registration-checkbox">
                                                @endif
                                                {{ $index + 1 }}
                                            </td>
                                            <td>{{ $registration->user->name }}</td>
                                            <td>{{ $registration->user->email }}</td>
                                            <td>{{ $registration->user->phone ?? '未提供' }}</td>
                                            <td>{{ $registration->created_at->format('Y/m/d H:i') }}</td>
                                            <td>
                                                @if ($registration->status == 'approved')
                                                    <span class="badge bg-success">已確認</span>
                                                @elseif($registration->status == 'pending')
                                                    <span class="badge bg-warning">待審核</span>
                                                @elseif($registration->status == 'rejected')
                                                    <span class="badge bg-danger">已拒絕</span>
                                                @elseif($registration->status == 'cancelled')
                                                    <span class="badge bg-secondary">已取消</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if ($registration->status == 'pending')
                                                        <form
                                                            action="{{ route('admin.registrations.approve', $registration) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="bi bi-check"></i> 核准
                                                            </button>
                                                        </form>
                                                        <form
                                                            action="{{ route('admin.registrations.destroy', $registration) }}"
                                                            method="POST" class="d-inline ms-1">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('確定要拒絕此報名嗎？這將從報名列表中刪除該記錄。')">
                                                                <i class="bi bi-x"></i> 拒絕
                                                            </button>
                                                        </form>
                                                    @elseif ($registration->status == 'approved')
                                                        <form
                                                            action="{{ route('admin.registrations.destroy', $registration) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-warning btn-sm"
                                                                onclick="return confirm('確定要取消此報名嗎？')">
                                                                <i class="bi bi-x-circle"></i> 取消報名
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i> 目前尚無人報名此活動
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- 新增報名的模態對話框 -->
    <div class="modal fade" id="addRegistrationModal" tabindex="-1" aria-labelledby="addRegistrationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRegistrationModalLabel">新增報名人員</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.registrations.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="activity_id" value="{{ $activity->id }}">

                        <div class="mb-3">
                            <label for="user_search" class="form-label">搜尋用戶</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="user_search"
                                    placeholder="輸入用戶名稱或電子郵件進行搜尋">
                                <button class="btn btn-outline-secondary" type="button" id="searchUserBtn">搜尋</button>
                            </div>
                            <div id="userSearchResults" class="mt-2 list-group"
                                style="max-height: 200px; overflow-y: auto;"></div>
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">選擇用戶</label>
                            <select class="form-select" name="user_id" id="user_id" required>
                                <option value="">-- 請選擇用戶 --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">報名狀態</label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="pending">待審核</option>
                                <option value="approved" selected>已核准</option>
                                <option value="rejected">已拒絕</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">備註</label>
                            <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">新增報名</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 全選/取消全選
            const selectAllCheckbox = document.getElementById('selectAll');
            const registrationCheckboxes = document.querySelectorAll('.registration-checkbox');
            const selectedCountSpan = document.getElementById('selectedCount');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    registrationCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                    updateSelectedCount();
                });
            }

            // 更新已選擇數量
            registrationCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            function updateSelectedCount() {
                const checkedCount = document.querySelectorAll('.registration-checkbox:checked').length;
                selectedCountSpan.textContent = checkedCount;
            }

            // 批量核准
            const bulkApproveBtn = document.querySelector('.bulk-approve');
            if (bulkApproveBtn) {
                bulkApproveBtn.addEventListener('click', function() {
                    processBulkAction('approve');
                });
            }

            // 批量拒絕
            const bulkRejectBtn = document.querySelector('.bulk-reject');
            if (bulkRejectBtn) {
                bulkRejectBtn.addEventListener('click', function() {
                    if (confirm('確定要拒絕所選的報名嗎？這將從報名列表中刪除這些記錄。')) {
                        processBulkAction('delete');
                    }
                });
            }

            // 處理批量操作
            function processBulkAction(action) {
                const checkedBoxes = document.querySelectorAll('.registration-checkbox:checked');

                if (checkedBoxes.length === 0) {
                    alert('請至少選擇一項報名記錄');
                    return;
                }

                // 創建正式的表單，而非隱藏表單
                const actionForm = document.createElement('form');
                actionForm.method = 'POST';
                actionForm.style.display = 'none';

                // 設定正確的路由
                if (action === 'approve') {
                    actionForm.action = "{{ route('admin.registrations.bulk-approve') }}";
                } else if (action === 'reject') {
                    actionForm.action = "{{ route('admin.registrations.bulk-delete') }}";
                }

                // 添加 CSRF 令牌 - 使用相同格式的令牌
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = "{{ csrf_token() }}";
                actionForm.appendChild(csrfInput);

                if (action === 'delete') {
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    actionForm.appendChild(methodInput);
                }

                // 添加選中的 ID
                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'registration_ids[]';
                    input.value = checkbox.value;
                    actionForm.appendChild(input);
                });

                // 添加到文檔中並提交
                document.body.appendChild(actionForm);
                actionForm.submit();
            }
            // 用戶搜索功能
            document.getElementById('searchUserBtn').addEventListener('click', function() {
                const searchTerm = document.getElementById('user_search').value;
                if (searchTerm.length < 2) {
                    alert('請至少輸入 2 個字符進行搜尋');
                    return;
                }

                const resultsContainer = document.getElementById('userSearchResults');
                resultsContainer.innerHTML =
                    '<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"></div> 搜尋中...</div>';

                fetch(`{{ route('admin.users.search') }}?term=${searchTerm}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsContainer.innerHTML = '';
                        const selectElement = document.getElementById('user_id');

                        // 清除當前選項
                        while (selectElement.options.length > 1) {
                            selectElement.remove(1);
                        }

                        if (data.length === 0) {
                            resultsContainer.innerHTML =
                                '<div class="alert alert-info">沒有找到匹配的用戶</div>';
                            return;
                        }

                        // 添加新選項
                        data.forEach(user => {
                            const option = new Option(`${user.name} (${user.email})`, user.id);
                            selectElement.add(option);

                            // 添加到搜索結果列表
                            const listItem = document.createElement('button');
                            listItem.type = 'button';
                            listItem.className = 'list-group-item list-group-item-action';
                            listItem.textContent = `${user.name} (${user.email})`;
                            listItem.addEventListener('click', function() {
                                selectElement.value = user.id;
                                resultsContainer.innerHTML = '';
                            });
                            resultsContainer.appendChild(listItem);
                        });
                    })
                    .catch(error => {
                        console.error('搜索用戶時出錯:', error);
                        resultsContainer.innerHTML = '<div class="alert alert-danger">搜尋失敗，請稍後再試</div>';
                    });
            });

            // 按 Enter 鍵搜索
            document.getElementById('user_search').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('searchUserBtn').click();
                }
            });
        });
    </script>
@endpush
