<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\AttributeValue;


class ChatbotController extends Controller
{
    // Trang chat
    public function index()
    {
        $messages = ChatMessage::orderBy('created_at', 'asc')->limit(50)->get();
        return view('user.chatbot.index', compact('messages'));
    }

    // Xử lý gửi tin nhắn
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $userMessage = trim($request->message);

        // Lưu tin nhắn người dùng
        ChatMessage::create([
            'message' => $userMessage,
            'type' => 'user',
        ]);

        // Lấy phản hồi từ bot
        $botMessage = $this->getBotResponse($userMessage);

        // Lưu tin nhắn bot
        ChatMessage::create([
            'message' => strip_tags($botMessage), // Lưu plain text
            'type' => 'bot',
        ]);

        return response()->json([
            'message' => $botMessage, // Trả về HTML
            'success' => true
        ]);
    }

    // Bot trả lời
    private function getBotResponse($message)
    {
        $message = strtolower(trim($message));

        // Xử lý các lệnh đặc biệt
        if ($message === 'clear' || $message === 'xóa' || $message === 'xoa') {
            ChatMessage::truncate();
            return "Đã xóa toàn bộ lịch sử chat!";
        }

        if ($message === 'help' || $message === 'trợ giúp' || $message === 'tro giup') {
            return $this->showHelp();
        }

        // Phân tích câu hỏi
        if (str_contains($message, 'giá') || str_contains($message, 'price') || str_contains($message, 'cost')) {
            return $this->handlePriceQuery($message);
        }
        elseif (str_contains($message, 'danh mục') || str_contains($message, 'category') || str_contains($message, 'loại')) {
            return $this->handleCategoryQuery();
        }
        elseif (str_contains($message, 'cấu hình') || str_contains($message, 'config') || str_contains($message, 'thông số')) {
            return $this->handleSpecQuery($message);
        }
        elseif (str_contains($message, 'khuyến mãi') || str_contains($message, 'sale') || str_contains($message, 'discount')) {
            return "Hiện tại chúng tôi có các chương trình khuyến mãi hấp dẫn. Vui lòng truy cập trang chủ để xem chi tiết!";
        }
        elseif (str_contains($message, 'kho') || str_contains($message, 'stock') || str_contains($message, 'tồn')) {
            return $this->handleStockQuery($message);
        }
        elseif (str_contains($message, 'xin chào') || str_contains($message, 'hello') || str_contains($message, 'hi') || $message === 'chào') {
            return "👋 <strong>Chào bạn!</strong> Tôi là trợ lý ảo BeeFast. Tôi có thể giúp bạn:\n• Tìm thông tin sản phẩm\n• Kiểm tra giá\n• Xem danh mục\n• Tư vấn cấu hình\nHãy cho tôi biết bạn cần gì!";
        }
        elseif (str_contains($message, 'cảm ơn') || str_contains($message, 'thanks') || str_contains($message, 'thank')) {
            return "👍 <strong>Không có gì!</strong> Rất vui được giúp bạn. Nếu cần thêm thông tin, cứ hỏi tôi nhé!";
        }
        elseif (str_contains($message, 'tạm biệt') || str_contains($message, 'bye') || str_contains($message, 'goodbye')) {
            return "👋 <strong>Tạm biệt bạn!</strong> Hẹn gặp lại. Đừng ngần ngại quay lại nếu cần hỗ trợ nhé!";
        }
           elseif ($message === 'sản phẩm' || $message === 'san pham' || $message === 'products') {
        return $this->showAllProducts();
    }
        else {
            // Tìm kiếm sản phẩm
            return $this->searchProduct($message);
        }
    }

    private function showAllProducts()
{
    $products = Product::limit(5)->get();

    if ($products->count() > 0) {
        $response = "📦 <strong>Tất cả sản phẩm hiện có:</strong>\n\n";

        foreach ($products as $product) {
            $variants = ProductVariant::where('product_id', $product->id)->get();
            $minPrice = $variants->min('price');
            $maxPrice = $variants->max('price');

            $priceRange = $minPrice == $maxPrice
                ? number_format($minPrice, 0, ',', '.') . ' VND'
                : number_format($minPrice, 0, ',', '.') . ' - ' . number_format($maxPrice, 0, ',', '.') . ' VND';

            $category = Category::find($product->category_id);
            $categoryName = $category ? $category->name : 'Chưa phân loại';

            $productUrl = route('products.show', $product->slug);

            $response .= "• <strong>{$product->name}</strong>\n";
            $response .= "  📂 Danh mục: {$categoryName}\n";
            $response .= "  💰 Giá: {$priceRange}\n";
            $response .= "  🔗 <a href=\"{$productUrl}\" target=\"_blank\" style=\"color: #28a745; text-decoration: none;\">Xem chi tiết →</a>\n\n";
        }

        return $response;
    }

    return "Hiện chưa có sản phẩm nào trong cửa hàng.";
}

    // Hiển thị trợ giúp
    private function showHelp()
    {
        return "🤖 <strong>Tôi có thể giúp bạn:</strong>\n\n" .
               "🔍 <strong>Tìm sản phẩm:</strong>\n" .
               "• 'BeeFast Pro X1'\n" .
               "• 'laptop gaming'\n" .
               "• 'máy tính văn phòng'\n\n" .
               "💰 <strong>Hỏi giá:</strong>\n" .
               "• 'giá BeeFast'\n" .
               "• 'BeeFast Pro X1 giá bao nhiêu'\n\n" .
               "📁 <strong>Danh mục:</strong>\n" .
               "• 'danh mục sản phẩm'\n" .
               "• 'có những loại laptop nào'\n\n" .
               "⚙️ <strong>Cấu hình:</strong>\n" .
               "• 'cấu hình BeeFast Gaming Z1'\n" .
               "• 'thông số kỹ thuật'\n\n" .
               "📦 <strong>Kho hàng:</strong>\n" .
               "• 'còn hàng không'\n" .
               "• 'kho BeeFast Pro X1'\n\n" .
               "🛒 <strong>Lệnh khác:</strong>\n" .
               "• 'clear' - Xóa lịch sử chat\n" .
               "• 'help' - Hiển thị trợ giúp";
    }

    // Xử lý câu hỏi về giá
    private function handlePriceQuery($message)
    {
        // Tìm tên sản phẩm trong câu hỏi
        $products = Product::all();
        $foundProduct = null;

        foreach ($products as $product) {
            $productName = strtolower($product->name);
            if (str_contains($message, $productName)) {
                $foundProduct = $product;
                break;
            }
        }

        if ($foundProduct) {
            $variants = ProductVariant::where('product_id', $foundProduct->id)->get();

            if ($variants->count() > 0) {
                $response = "💰 <strong>Giá {$foundProduct->name}:</strong>\n\n";
                foreach ($variants as $variant) {
                    $specs = $this->getVariantSpecs($variant->id);
                    $price = number_format($variant->price, 0, ',', '.') . ' VND';
                    $stock = $variant->stock > 0 ? "✅ Còn {$variant->stock} cái" : "❌ Hết hàng";

                    $response .= "• <strong>{$variant->sku}</strong>: {$price}\n";
                    $response .= "  {$specs}\n";
                    $response .= "  {$stock}\n\n";
                }
                $productUrl = route('products.show', $foundProduct->slug);
                $response .= "🔗 <a href=\"{$productUrl}\" target=\"_blank\" style=\"color: #28a745; text-decoration: none;\"><strong>Xem chi tiết sản phẩm →</strong></a>";
                return $response;
            }
        }

        return "Bạn muốn biết giá sản phẩm nào? Vui lòng nhập tên sản phẩm cụ thể (ví dụ: 'BeeFast Pro X1', 'BeeFast Gaming Z1').";
    }

    // Xử lý danh mục
    private function handleCategoryQuery()
    {
        $categories = Category::where('status', 1)->get();

        if ($categories->count() > 0) {
            $response = "📁 <strong>Danh mục sản phẩm:</strong>\n\n";
            foreach ($categories as $category) {
                $productCount = Product::where('category_id', $category->id)->count();
                $categoryUrl = route('products.index', ['category' => $category->id]);
                $response .= "• <strong>{$category->name}</strong> - {$productCount} sản phẩm\n";
                $response .= "  <a href=\"{$categoryUrl}\" target=\"_blank\" style=\"color: #6c757d; text-decoration: none;\">Xem sản phẩm →</a>\n";
                $response .= "  <em>{$category->description}</em>\n\n";
            }
            return $response;
        }

        return "Hiện chưa có danh mục sản phẩm nào.";
    }

    // Xử lý câu hỏi về cấu hình
    private function handleSpecQuery($message)
    {
        $products = Product::all();
        $foundProduct = null;

        foreach ($products as $product) {
            $productName = strtolower($product->name);
            if (str_contains($message, $productName)) {
                $foundProduct = $product;
                break;
            }
        }

        if ($foundProduct) {
            $variants = ProductVariant::where('product_id', $foundProduct->id)->get();

            if ($variants->count() > 0) {
                $response = "⚙️ <strong>Cấu hình {$foundProduct->name}:</strong>\n\n";
                foreach ($variants as $index => $variant) {
                    $specs = $this->getVariantSpecs($variant->id);
                    $response .= "<strong>Phiên bản " . ($index + 1) . " ({$variant->sku}):</strong>\n";
                    $response .= $specs . "\n\n";
                }
                $productUrl = route('products.show', $foundProduct->slug);
                $response .= "🔗 <a href=\"{$productUrl}\" target=\"_blank\" style=\"color: #28a745; text-decoration: none;\"><strong>Xem đầy đủ thông số →</strong></a>";
                return $response;
            }
        }

        return "Bạn muốn xem cấu hình sản phẩm nào? Vui lòng nhập tên sản phẩm cụ thể.";
    }

    // Xử lý câu hỏi về kho hàng
    private function handleStockQuery($message)
    {
        $products = Product::all();
        $foundProduct = null;

        foreach ($products as $product) {
            $productName = strtolower($product->name);
            if (str_contains($message, $productName)) {
                $foundProduct = $product;
                break;
            }
        }

        if ($foundProduct) {
            $variants = ProductVariant::where('product_id', $foundProduct->id)->get();
            $totalStock = 0;

            $response = "📦 <strong>Tình trạng kho - {$foundProduct->name}:</strong>\n\n";
            foreach ($variants as $variant) {
                $status = $variant->stock > 0 ? "✅ Còn hàng ({$variant->stock} cái)" : "❌ Hết hàng";
                $response .= "• {$variant->sku}: {$status}\n";
                $totalStock += $variant->stock;
            }

            $response .= "\n📊 <strong>Tổng tồn kho:</strong> {$totalStock} cái\n";

            if ($totalStock > 0) {
                $productUrl = route('products.show', $foundProduct->slug);
                $response .= "\n🔗 <a href=\"{$productUrl}\" target=\"_blank\" style=\"color: #28a745; text-decoration: none;\"><strong>Mua ngay →</strong></a>";
            }

            return $response;
        }

        return "Bạn muốn kiểm tra kho sản phẩm nào? Vui lòng nhập tên sản phẩm.";
    }

    // Tìm kiếm sản phẩm
    private function searchProduct($keyword)
    {
         // Nếu từ khóa là "sản phẩm laptop" hoặc tương tự, hiển thị tất cả
    if (in_array($keyword, ['sản phẩm', 'san pham', 'sản phẩm laptop', 'laptop', 'máy tính'])) {
        return $this->showAllProducts();
    }
        // Tìm theo tên sản phẩm
        $products = Product::where('name', 'like', "%{$keyword}%")
                          ->orWhere('description', 'like', "%{$keyword}%")
                          ->limit(5)
                          ->get();

        if ($products->count() > 0) {
            $response = "🔍 <strong>Tìm thấy {$products->count()} sản phẩm:</strong>\n\n";

            foreach ($products as $product) {
                $variants = ProductVariant::where('product_id', $product->id)->get();
                $minPrice = $variants->min('price');
                $maxPrice = $variants->max('price');

                $priceRange = $minPrice == $maxPrice
                    ? number_format($minPrice, 0, ',', '.') . ' VND'
                    : number_format($minPrice, 0, ',', '.') . ' - ' . number_format($maxPrice, 0, ',', '.') . ' VND';

                $category = Category::find($product->category_id);
                $categoryName = $category ? $category->name : 'Chưa phân loại';

                $productUrl = route('products.show', $product->slug);

                $response .= "• <strong>{$product->name}</strong>\n";
                $response .= "  📂 Danh mục: {$categoryName}\n";
                $response .= "  💰 Giá: {$priceRange}\n";
                $response .= "  📝 " . substr($product->description, 0, 100) . "...\n";
                $response .= "  🔗 <a href=\"{$productUrl}\" target=\"_blank\" style=\"color: #28a745; text-decoration: none;\">Xem ngay →</a>\n\n";
            }


            return $response;
        }

        // Nếu không tìm thấy sản phẩm, gợi ý
        $allProducts = Product::pluck('name')->toArray();
        $suggestions = implode(", ", array_slice($allProducts, 0, 5));

        return "❌ <strong>Không tìm thấy sản phẩm phù hợp với '{$keyword}'.</strong>\n\n" .
               "💡 <strong>Gợi ý tìm kiếm:</strong>\n" .
               "• BeeFast Pro X1\n" .
               "• BeeFast Gaming Z1\n" .
               "• BeeFast Ultra S1\n" .
               "• BeeFast Design D1\n\n" .
               "Hoặc bạn có thể:\n" .
               "• Xem 'danh mục' sản phẩm\n" .
               "• Hỏi 'giá' sản phẩm cụ thể\n" .
               "• Gõ 'help' để xem hướng dẫn";
    }

    // Lấy thông số kỹ thuật của variant
    private function getVariantSpecs($variantId)
    {
        $specs = AttributeValue::where('product_variant_id', $variantId)
            ->join('attribute_values', 'variant_attribute_values.attribute_value_id', '=', 'attribute_values.id')
            ->join('attributes', 'attribute_values.attribute_id', '=', 'attributes.id')
            ->select('attributes.name as attr_name', 'attribute_values.value as attr_value')
            ->get();

        $result = [];
        foreach ($specs as $spec) {
            $result[] = "{$spec->attr_name}: {$spec->attr_value}";
        }

        return implode(" | ", $result);
    }

    // Xóa lịch sử chat (API riêng)
    public function clearHistory()
    {
        ChatMessage::truncate();
        return response()->json(['success' => true, 'message' => 'Đã xóa lịch sử chat']);
    }
}
