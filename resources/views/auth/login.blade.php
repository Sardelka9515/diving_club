<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h2 class="fw-bold mb-0">潛水社會員登入</h2>
                        <p class="mt-2 mb-0">使用您的中央大學 Portal 帳號登入</p>
                    </div>
                    <div class="card-body p-4 p-md-5 text-center">
                        @if(session('status'))
                            <div class="alert alert-info mb-4">{{ session('status') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                        @endif
                        <form method="GET" action="{{ route('login') }}">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> 登入 NCU Portal
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>