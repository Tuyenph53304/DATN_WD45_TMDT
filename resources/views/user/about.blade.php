@extends('user.layout')

@section('title', 'Giới thiệu - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-3">Giới thiệu về chúng tôi</h1>
                <p class="lead text-muted">Địa chỉ tin cậy cho mọi nhu cầu công nghệ của bạn</p>
            </div>

            <!-- About Section -->
            <div class="card border-0 shadow-sm mb-5" style="border-radius: var(--radius-xl);">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4"><i class="bi bi-info-circle text-primary me-2"></i> Về chúng tôi</h2>
                    <p class="lead mb-4">
                        Chúng tôi là một trong những cửa hàng công nghệ hàng đầu, chuyên cung cấp các sản phẩm laptop, 
                        máy tính và phụ kiện công nghệ chất lượng cao với giá cả hợp lý nhất thị trường.
                    </p>
                    <p>
                        Với hơn 10 năm kinh nghiệm trong ngành, chúng tôi tự hào là đối tác tin cậy của hàng nghìn khách hàng 
                        trên toàn quốc. Chúng tôi cam kết mang đến những sản phẩm chính hãng, chất lượng cao cùng dịch vụ 
                        chăm sóc khách hàng tận tâm.
                    </p>
                </div>
            </div>

            <!-- Mission & Vision -->
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: var(--radius-xl);">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-bullseye text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h3 class="fw-bold text-center mb-3">Sứ mệnh</h3>
                            <p class="text-center text-muted">
                                Mang đến cho khách hàng những sản phẩm công nghệ tốt nhất với giá cả hợp lý, 
                                dịch vụ chuyên nghiệp và trải nghiệm mua sắm tuyệt vời nhất.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: var(--radius-xl);">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-eye text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h3 class="fw-bold text-center mb-3">Tầm nhìn</h3>
                            <p class="text-center text-muted">
                                Trở thành cửa hàng công nghệ hàng đầu Việt Nam, được khách hàng tin tưởng và lựa chọn 
                                cho mọi nhu cầu công nghệ của họ.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Values -->
            <div class="card border-0 shadow-sm mb-5" style="border-radius: var(--radius-xl);">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4"><i class="bi bi-star-fill text-warning me-2"></i> Giá trị cốt lõi</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-shield-check text-success" style="font-size: 2.5rem;"></i>
                                <h5 class="fw-bold mt-3 mb-2">Chất lượng</h5>
                                <p class="text-muted small">
                                    Chúng tôi chỉ cung cấp những sản phẩm chính hãng, đảm bảo chất lượng tốt nhất.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-heart-fill text-danger" style="font-size: 2.5rem;"></i>
                                <h5 class="fw-bold mt-3 mb-2">Tận tâm</h5>
                                <p class="text-muted small">
                                    Đội ngũ nhân viên chuyên nghiệp, luôn sẵn sàng hỗ trợ khách hàng 24/7.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bi bi-tag-fill text-primary" style="font-size: 2.5rem;"></i>
                                <h5 class="fw-bold mt-3 mb-2">Giá tốt</h5>
                                <p class="text-muted small">
                                    Giá cả cạnh tranh nhất thị trường, nhiều chương trình khuyến mãi hấp dẫn.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="card border-0 shadow-sm mb-5" style="border-radius: var(--radius-xl);">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4"><i class="bi bi-gear-fill text-info me-2"></i> Dịch vụ của chúng tôi</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-truck text-primary" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Giao hàng nhanh chóng</h5>
                                    <p class="text-muted small mb-0">
                                        Giao hàng toàn quốc, miễn phí vận chuyển cho đơn hàng trên 2 triệu đồng.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Bảo hành chính hãng</h5>
                                    <p class="text-muted small mb-0">
                                        Tất cả sản phẩm đều có bảo hành chính hãng, hỗ trợ đổi trả trong 7 ngày.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-headset text-warning" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Hỗ trợ 24/7</h5>
                                    <p class="text-muted small mb-0">
                                        Đội ngũ tư vấn chuyên nghiệp, sẵn sàng hỗ trợ bạn mọi lúc mọi nơi.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-credit-card text-danger" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Thanh toán linh hoạt</h5>
                                    <p class="text-muted small mb-0">
                                        Hỗ trợ nhiều phương thức thanh toán: COD, MoMo, VNPay, chuyển khoản.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card border-0 shadow-sm" style="border-radius: var(--radius-xl); background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);">
                <div class="card-body p-5 text-white text-center">
                    <h2 class="fw-bold mb-4">Liên hệ với chúng tôi</h2>
                    <p class="lead mb-4">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn</p>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <i class="bi bi-telephone-fill mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="fw-bold">Hotline</h5>
                            <p class="mb-0">1900 1234</p>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-envelope-fill mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="fw-bold">Email</h5>
                            <p class="mb-0">support@example.com</p>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-geo-alt-fill mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="fw-bold">Địa chỉ</h5>
                            <p class="mb-0">123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
