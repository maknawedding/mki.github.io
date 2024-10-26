<?php

namespace App\Http\Controllers;

use App\Interfaces\BoardingHouseRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use Illuminate\Http\Request;

class BoardingHouseController extends Controller
{
    private CityRepositoryInterface $cityRepository;
    private BoardingHouseRepositoryInterface $boardingHouseRepository;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        CityRepositoryInterface $cityRepository,
        BoardingHouseRepositoryInterface $boardingHouseRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->cityRepository = $cityRepository;
        $this->boardingHouseRepository = $boardingHouseRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function show($slug)
    {
        $boardingHouse = $this->boardingHouseRepository->getBoardingHouseBySlug($slug);

        return view('pages.boarding-house.show', compact('boardingHouse'));
    }

    public function rooms($slug)
    {
        $boardingHouse = $this->boardingHouseRepository->getBoardingHouseBySlug($slug);
        
        return view('pages.boarding-house.rooms', compact('boardingHouse'));

    }
    public function find()
    {
        $categories = $this->categoryRepository->getAllCategories();
        $cities = $this->cityRepository->getAllCities();
        
        return view('pages.boarding-house.find', compact('categories', 'cities'));
    }
}
