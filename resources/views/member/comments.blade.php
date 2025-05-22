@extends('layouts.app')

@section('title', '我的評論')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h1 class="mb-3 mb-md-0"><i class="bi bi-chat-dots me-2 text-primary"></i>我的評論</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> 返回儀表板
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <!-- Comments Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-md-3">
                    <div class="card ocean-card h-100" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                                <i class="bi bi-chat-dots-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">總評論數</h6>
                                <h3 class="mb-0">{{ $comments->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-3">
                    <div class="card ocean-card h-100" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">已審核評論</h6>
                                <h3 class="mb-0">{{ $comments->where('status', 'approved')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-3">
                    <div class="card ocean-card h-100" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-circle bg-warning bg-opacity-10 text-warning me-3">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">待審核評論</h6>
                                <h3 class="mb-0">{{ $comments->where('status', 'pending')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-3">
                    <div class="card ocean-card h-100" data-aos="fade-up" data-aos-delay="400">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-circle bg-info bg-opacity-10 text-info me-3">
                                <i class="bi bi-reply-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">回覆數量</h6>
                                <h3 class="mb-0">{{ $comments->whereNotNull('parent_id')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Filter & Search -->
            <div class="card ocean-card mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body">
                    <form action="{{ route('member.comments') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">評論狀態</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">全部狀態</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>已審核</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>待審核</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>被拒絕</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="type" class="form-label">評論類型</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">全部類型</option>
                                <option value="comment" {{ request('type') == 'comment' ? 'selected' : '' }}>主評論</option>
                                <option value="reply" {{ request('type') == 'reply' ? 'selected' : '' }}>回覆</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="search" class="form-label">關鍵詞搜尋</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                placeholder="搜尋評論內容..." value="{{ request('search') }}">
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="d-grid gap-2 w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> 搜尋
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Comments List -->
            <div class="card ocean-card" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header bg-transparent border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>評論列表</h5>
                        <span class="badge bg-primary">{{ $comments->total() }} 則評論</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($comments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50%">評論內容</th>
                                        <th>活動</th>
                                        <th>狀態</th>
                                        <th>時間</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($comments as $comment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-start">
                                                    @if($comment->parent_id)
                                                        <span class="badge bg-info me-2 align-self-center">回覆</span>
                                                    @endif
                                                    <div class="comment-content">
                                                        <p class="mb-1 comment-text">{{ Str::limit($comment->content, 100) }}</p>
                                                        @if($comment->parent_id)
                                                            <small class="text-muted">
                                                                回覆給: {{ $comment->parent->user->name ?? '已刪除用戶' }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('activities.show', $comment->activity_id) }}#comment-{{ $comment->id }}" 
                                                   class="text-decoration-none">
                                                    {{ Str::limit($comment->activity->title ?? '已刪除活動', 25) }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($comment->status == 'approved')
                                                    <span class="badge bg-success">已審核</span>
                                                @elseif($comment->status == 'pending')
                                                    <span class="badge bg-warning text-dark">待審核</span>
                                                @elseif($comment->status == 'rejected')
                                                    <span class="badge bg-danger">被拒絕</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ $comment->created_at->format('Y/m/d') }}</span>
                                                    <small class="text-muted">{{ $comment->created_at->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary view-comment-btn" 
                                                            data-bs-toggle="modal" data-bs-target="#viewCommentModal-{{ $comment->id }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    
                                                    <button type="button" class="btn btn-sm btn-outline-primary edit-comment-btn"
                                                            data-bs-toggle="modal" data-bs-target="#editCommentModal-{{ $comment->id }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            data-bs-toggle="modal" data-bs-target="#deleteCommentModal-{{ $comment->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $comments->appends(request()->query())->links() }}
                        </div>
                        
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-chat-square-text text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="mb-2">暫無評論</h5>
                            <p class="text-muted mb-4">您尚未發表任何評論或回覆。</p>
                            <a href="{{ route('activities.index') }}" class="btn btn-ocean">
                                <i class="bi bi-calendar-event me-1"></i> 瀏覽活動
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for each comment -->
@foreach($comments as $comment)
    <!-- View Comment Modal -->
    <div class="modal fade" id="viewCommentModal-{{ $comment->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">查看評論</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-medium">評論內容</label>
                        <div class="p-3 bg-light rounded">{{ $comment->content }}</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">活動</label>
                            <p class="mb-0">{{ $comment->activity->title ?? '已刪除活動' }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">狀態</label>
                            <div>
                                @if($comment->status == 'approved')
                                    <span class="badge bg-success">已審核</span>
                                @elseif($comment->status == 'pending')
                                    <span class="badge bg-warning text-dark">待審核</span>
                                @elseif($comment->status == 'rejected')
                                    <span class="badge bg-danger">被拒絕</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">發布時間</label>
                            <p class="mb-0">{{ $comment->created_at->format('Y/m/d H:i:s') }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">最後更新</label>
                            <p class="mb-0">{{ $comment->updated_at->format('Y/m/d H:i:s') }}</p>
                        </div>
                    </div>
                    
                    @if($comment->parent_id)
                        <div class="mt-2">
                            <label class="form-label fw-medium">回覆對象</label>
                            <div class="p-3 border rounded">
                                <p class="mb-1 small">{{ $comment->parent->content ?? '原評論已刪除' }}</p>
                                <p class="mb-0 small text-muted">- {{ $comment->parent->user->name ?? '用戶已刪除' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                    <a href="{{ route('activities.show', $comment->activity_id) }}#comment-{{ $comment->id }}" 
                       class="btn btn-primary">
                        <i class="bi bi-box-arrow-up-right me-1"></i> 前往查看
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Comment Modal -->
    <div class="modal fade" id="editCommentModal-{{ $comment->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">編輯評論</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="content-{{ $comment->id }}" class="form-label">評論內容</label>
                            <textarea class="form-control" id="content-{{ $comment->id }}" 
                                      name="content" rows="5" required>{{ $comment->content }}</textarea>
                            <div class="form-text">
                                評論將再次送交審核，審核通過後才會顯示。
                            </div>
                        </div>
                        
                        @if($comment->parent_id)
                            <div class="mb-3">
                                <label class="form-label">回覆對象</label>
                                <div class="p-3 border rounded bg-light">
                                    <p class="mb-1 small">{{ $comment->parent->content ?? '原評論已刪除' }}</p>
                                    <p class="mb-0 small text-muted">- {{ $comment->parent->user->name ?? '用戶已刪除' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> 更新評論
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Comment Modal -->
    <div class="modal fade" id="deleteCommentModal-{{ $comment->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">刪除評論</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <p>您確定要刪除此評論嗎？此操作無法撤銷。</p>
                    <div class="alert alert-secondary">
                        <p class="mb-0"><strong>評論內容:</strong> {{ Str::limit($comment->content, 100) }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> 確認刪除
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@push('styles')
<style>
    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .comment-text {
        line-height: 1.4;
    }
    
    .table th, .table td {
        padding: 1rem 1.25rem;
    }
    
    .table tbody tr:hover {
        background-color: rgba(117, 230, 218, 0.05);
    }
    
    /* Fix for any modal issues */
    .modal-backdrop {
        z-index: 1040;
    }
    
    .modal {
        z-index: 1050;
    }
    
    .modal-dialog {
        margin: 1.75rem auto;
    }
    
    /* Custom styling for modals to match ocean theme */
    .modal-content {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
        background-color: var(--ocean-foam);
        border-bottom: none;
    }
    
    /* Responsive table adjustments */
    @media (max-width: 767.98px) {
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .comment-text {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle modals and ensure they work properly
        var modals = document.querySelectorAll('.modal');
        
        modals.forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                // Ensure the backdrop is behind the modal
                var backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(function(backdrop) {
                    backdrop.style.zIndex = '1040';
                });
                
                // Ensure modal is above backdrop
                this.style.zIndex = '1050';
            });
        });
        
        // Handle filter form submission
        const filterForm = document.querySelector('form');
        const statusSelect = document.getElementById('status');
        const typeSelect = document.getElementById('type');
        const searchInput = document.getElementById('search');
        
        // Reset filters button functionality could be added if needed
        
        // Handle expanding comment text on click if truncated
        const commentTexts = document.querySelectorAll('.comment-text');
        commentTexts.forEach(function(text) {
            let fullText = text.textContent;
            let isExpanded = false;
            
            if (fullText.length > 100) {
                text.addEventListener('click', function() {
                    if (!isExpanded) {
                        text.textContent = fullText;
                        isExpanded = true;
                    } else {
                        text.textContent = fullText.substring(0, 100) + '...';
                        isExpanded = false;
                    }
                });
                
                // Add hover styling to indicate expandability
                text.style.cursor = 'pointer';
                text.title = '點擊展開/收起全文';
            }
        });
    });
</script>
@endpush
@endsection