<?php
declare(strict_types=1);

namespace ConsultMee\Controllers;

use ConsultMee\Core\Controller;
use ConsultMee\Models\Consultant;
use ConsultMee\Models\Rating;

class ConsultantController extends Controller
{
    public function dashboard(): void
    {
        $this->view('consultant/dashboard', [
            'title'      => 'Consultant Dashboard | ConsultMee',
            'consultant' => $this->consultant(),
        ], 'layouts/consultant');
    }

    public function publicProfile(): void
    {
        $username   = $this->request()->param('username', '');
        $model      = new Consultant();
        $consultant = $model->withRatings($username);

        if (!$consultant) {
            http_response_code(404);
            $this->view('errors/404', [], null);
            return;
        }

        $ratingModel = new Rating();
        $reviews     = $ratingModel->getByConsultant($username);

        $this->view('consultant/profile', [
            'title'      => $consultant['name'] . ' | ConsultMee',
            'consultant' => $consultant,
            'reviews'    => $reviews,
            'avgRating'  => $consultant['avg_rating'],
            'totalReviews' => (int)$consultant['total_reviews'],
        ]);
    }
}
