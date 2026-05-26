<?php
class CategoryController extends Controller {

    public function index($slug) {
        $categoryModel = $this->model('Category');
        $placeModel    = $this->model('Place');

        $category = $categoryModel->getBySlug($slug);
        if (!$category) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $places     = $placeModel->getByCategoryId($category->id);
        $categories = $categoryModel->getAllWithSlug();

        $catName  = $category->name;
        $count    = count($places);
        $seoTitle = $catName . 'รังสิต ทั้งหมด ' . $count . ' แห่ง - Discover Rangsit';
        $seoDesc  = 'รวม' . $catName . 'ในรังสิต ' . $count . ' แห่ง ค้นหา' . $catName . 'รังสิต ' . $catName . 'นครรังสิต ที่ดีที่สุด พร้อมรีวิว แผนที่ ข้อมูลติดต่อ และเวลาเปิด-ปิด';

        $this->view('places/category', [
            'title'       => $seoTitle,
            'description' => $seoDesc,
            'keywords'    => $catName . 'รังสิต, ' . $catName . 'นครรังสิต, ' . $catName . 'ย่านรังสิต, ของดีรังสิต, Discover Rangsit, ' . $catName,
            'og_url'      => BASE_URL . '/category/' . $category->slug,
            'category'    => $category,
            'places'      => $places,
            'categories'  => $categories,
        ]);
    }
}
