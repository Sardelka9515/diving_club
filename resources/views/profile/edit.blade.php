@extends('layouts.app')

@section('title', '個人資料')

@section('content')
<div class="container">
    <div class="row">
        <!-- 側邊欄 -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="avatar-placeholder rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                            <i class="bi bi-person-fill text-white" style="font-size: 60px;"></i>
                        </div>
                        <h5 class="mt-3 mb-1">{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">快速連結</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house"></i> 儀表板
                        </a>
                        <a href="{{ route('member.activities') }}" class="btn btn-outline-primary">
                            <i class="bi bi-calendar"></i> 我的活動
                        </a>
                        <a href="{{ route('activities.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-list"></i> 瀏覽活動
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 主要內容 -->
        <div class="col-md-9">
            <!-- 個人資料編輯 -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">個人資料</h5>
                </div>
                <div class="card-body">
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">姓名 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">電子郵件 <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div class="mt-2">
                                            <p class="text-warning small">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                您的電子郵件地址尚未驗證。
                                                <button form="send-verification" class="btn btn-link p-0 text-decoration-none">
                                                    點擊此處重新發送驗證郵件
                                                </button>
                                            </p>

                                            @if (session('status') === 'verification-link-sent')
                                                <p class="text-success small">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    新的驗證連結已發送到您的電子郵件地址。
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">聯絡電話</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emergency_contact" class="form-label">緊急聯絡人</label>
                                    <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emergency_phone" class="form-label">緊急聯絡電話</label>
                                    <input type="tel" class="form-control" id="emergency_phone" name="emergency_phone" value="{{ old('emergency_phone', $user->emergency_phone ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="birth_date" class="form-label">出生日期</label>
                                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="diving_experience" class="form-label">潛水經驗</label>
                            <select class="form-control" id="diving_experience" name="diving_experience">
                                <option value="">請選擇</option>
                                <option value="none" {{ old('diving_experience', $user->diving_experience ?? '') == 'none' ? 'selected' : '' }}>無經驗</option>
                                <option value="beginner" {{ old('diving_experience', $user->diving_experience ?? '') == 'beginner' ? 'selected' : '' }}>初學者</option>
                                <option value="intermediate" {{ old('diving_experience', $user->diving_experience ?? '') == 'intermediate' ? 'selected' : '' }}>中級</option>
                                <option value="advanced" {{ old('diving_experience', $user->diving_experience ?? '') == 'advanced' ? 'selected' : '' }}>進階</option>
                                <option value="expert" {{ old('diving_experience', $user->diving_experience ?? '') == 'expert' ? 'selected' : '' }}>專家</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="diving_certification" class="form-label">潛水證照</label>
                            <input type="text" class="form-control" id="diving_certification" name="diving_certification" 
                                   value="{{ old('diving_certification', $user->diving_certification ?? '') }}" 
                                   placeholder="例如：PADI OW, AOW, Rescue">
                        </div>

                        <div class="mb-3">
                            <label for="medical_conditions" class="form-label">健康狀況/醫療記錄</label>
                            <textarea class="form-control" id="medical_conditions" name="medical_conditions" rows="3" 
                                      placeholder="請說明任何相關的健康狀況或醫療記錄">{{ old('medical_conditions', $user->medical_conditions ?? '') }}</textarea>
                            <div class="form-text">這些資訊僅供安全考量使用，不會公開顯示。</div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>儲存變更
                            </button>

                            @if (session('status') === 'profile-updated')
                                <div class="alert alert-success alert-sm d-flex align-items-center mb-0" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    個人資料已成功更新
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- 修改密碼 -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">修改密碼</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="update_password_current_password" class="form-label">目前密碼 <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                   id="update_password_current_password" name="current_password" required autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="update_password_password" class="form-label">新密碼 <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                   id="update_password_password" name="password" required autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="update_password_password_confirmation" class="form-label">確認新密碼 <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                                   id="update_password_password_confirmation" name="password_confirmation" required autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-shield-lock me-1"></i>更新密碼
                            </button>

                            @if (session('status') === 'password-updated')
                                <div class="alert alert-success alert-sm d-flex align-items-center mb-0" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    密碼已成功更新
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- 刪除帳號 -->
            <div class="card mt-4 border-danger">
                <div class="card-header bg-danger bg-opacity-10">
                    <h5 class="card-title mb-0 text-danger">刪除帳號</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        刪除您的帳號後，所有資源和數據將被永久刪除。在刪除帳號之前，請下載您希望保留的任何數據或信息。
                    </p>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash me-1"></i>刪除帳號
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 刪除帳號確認模態框 -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">確認刪除帳號</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <p class="text-danger fw-bold">
                        您確定要刪除您的帳號嗎？
                    </p>
                    <p>
                        這個操作無法復原。您的所有數據將被永久刪除。
                    </p>
                    <div class="mb-3">
                        <label for="password" class="form-label">請輸入您的密碼以確認刪除：</label>
                        <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger">刪除帳號</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection