<div class="modal fade" id="reportModal-{{ $commentId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-flag me-2"></i>舉報不當評論</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('comments.report', $comment) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">評論內容</label>
                        <div class="border p-3 rounded-3 bg-light">
                            {{ $content }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="reason-{{ $commentId }}" class="form-label">舉報原因</label>
                        <select class="form-select ocean-form" id="reason-{{ $commentId }}" name="reason" required>
                            <option value="">請選擇舉報原因...</option>
                            <option value="dislike">我就是不喜歡</option>
                            <option value="harassment">霸凌或擾人的聯繫</option>
                            <option value="self_harm">自殺、自殘或飲食失調</option>
                            <option value="violence_hate">暴力、仇恨或剝削</option>
                            <option value="regulated_goods">販售或推廣管制商品</option>
                            <option value="nudity">裸露或性行為</option>
                            <option value="fraud_spam">詐騙、詐欺或垃圾訊息</option>
                            <option value="false_info">不實資訊</option>
                            <option value="other">其他原因</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="details-{{ $commentId }}" class="form-label">詳細說明 (選填)</label>
                        <textarea class="form-control ocean-form" id="details-{{ $commentId }}" name="details" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-flag-fill me-1"></i>提交舉報
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
