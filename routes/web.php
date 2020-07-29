<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix'     => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']
    ],
    function () {
        Route::get('/', 'PagesController@home');

        Route::get('blog/{article_slug}/{translation_slug?}', 'PagesController@article');

        Route::get('categories/{slug}', 'PagesController@category');

        Route::get('tags/{tag}/{slug?}', 'PagesController@tag');
});


Route::get('translations/{translation}/comments', 'CommentsController@index');
Route::post('translations/{translation}/comments', 'CommentsController@store');
Route::post('comments/{comment}/replies', 'RepliesController@store');
Route::post('flagged-comments', 'FlaggedCommentsController@store');
Route::post('flagged-replies', 'FlaggedRepliesController@store');

Route::group(['prefix' => 'admin', 'namespace' => 'Auth'], function () {
    Route::view('login', 'auth.login')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout');

    Route::view('password/request', 'auth.password-request')->name('password.request');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'ResetPasswordController@reset')
         ->name('password.update');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'namespace' => 'Admin'], function () {
    Route::get('me', 'ProfileController@show');
    Route::post('me', 'ProfileController@update');
    Route::post('me/password', 'UserPasswordController@update');

    Route::get('users', 'UsersController@index');
    Route::post('users', 'UsersController@store');
    Route::delete('users/{user}', 'UsersController@destroy');

    Route::get('categories', 'CategoriesController@index');
    Route::post('categories', 'CategoriesController@store');
    Route::post('categories/{category}', 'CategoriesController@update');
    Route::delete('categories/{category}', 'CategoriesController@destroy');

    Route::get('articles', 'ArticlesController@index');
    Route::post('articles', 'ArticlesController@store');
    Route::delete('articles/{article}', 'ArticlesController@destroy');
    Route::post('articles/{article}/categories', 'ArticleCategoriesController@update');
    Route::post('articles/{article}/translations', 'TranslationsController@store');
    Route::post('articles/{article}/title-image', 'ArticleTitleImageController@store');

    Route::get('search/articles', 'ArticleSearchController@index');

    Route::get('translations/{translation}', 'TranslationsController@show');
    Route::post('translations/{translation}', 'TranslationsController@update');
    Route::post('translations/{translation}/images', 'TranslationBodyImagesController@store');

    Route::post('published-translations', 'PublishedTranslationsController@store');
    Route::delete('published-translations/{translation}', 'PublishedTranslationsController@destroy');

    Route::get('site-settings/slide-count', 'SlideCountSettingController@show');
    Route::post('site-settings/slide-count', 'SlideCountSettingController@update');

    Route::get('slider/slides', 'SlidesController@index');
    Route::post('slider/slides', 'SlidesController@store');
    Route::delete('slider/{position}', 'SlidesController@destroy');

    Route::get('comments', 'CommentsController@index');
    Route::delete('comments/{comment}', 'CommentsController@destroy');

    Route::get('replies', 'RepliesController@index');
    Route::delete('replies/{reply}', 'RepliesController@destroy');

    Route::get('flagged-comments', 'FlaggedCommentsController@index');
    Route::delete('rejected-flags/{flagged}', 'RejectedFlagsController@destroy');
    Route::delete('enforced-flags/{flagged}', 'EnforcedFlagsController@destroy');

    Route::get('tags', 'TagsController@index');
    Route::delete('tags', 'TagsController@destroy');

    Route::get('tags/{tag}/translations', 'TagTranslationsController@index');

    Route::post('events', 'EventsController@store');
    Route::post('events/{event}/general-info', 'EventGeneralInfoController@update');

    Route::post('events/{event}/races', 'EventRacesController@store');
    Route::post('races/{race}', 'EventRacesController@update');
    Route::delete('races/{race}', 'EventRacesController@delete');

    Route::post('events/{event}/activities', 'EventActivitiesController@store');
    Route::post('activities/{activity}', 'EventActivitiesController@update');
    Route::delete('activities/{activity}', 'EventActivitiesController@delete');

    Route::post('events/{event}/schedule', 'EventScheduleController@update');
    Route::delete('events/{event}/schedule', 'EventScheduleController@destroy');

    Route::post('events/{event}/fees', 'EventFeesController@update');
    Route::delete('events/{event}/fees', 'EventFeesController@destroy');

    Route::post('events/{event}/prizes', 'EventPrizesController@update');
    Route::delete('events/{event}/prizes', 'EventPrizesController@destroy');

    Route::post('events/{event}/travel-routes', 'EventTravelRoutesController@store');
    Route::post('travel-routes/{route}', 'EventTravelRoutesController@update');
    Route::delete('travel-routes/{route}', 'EventTravelRoutesController@delete');

    Route::post('events/{event}/travel-guide', 'EventTravelGuideController@store');
    Route::delete('events/{event}/travel-guide', 'EventTravelGuideController@destroy');

    Route::post('events/{event}/accommodation', 'EventAccommodationsController@store');
    Route::post('accommodations/{accommodation}', 'EventAccommodationsController@update');
    Route::delete('accommodations/{accommodation}', 'EventAccommodationsController@delete');

    Route::post('events/{event}/courses', 'EventCoursesController@store');
    Route::post('courses/{course}', 'EventCoursesController@update');

    Route::post('courses/{course}/gpx-file', 'CourseGPXFileController@store');
    Route::delete('courses/{course}/gpx-file', 'CourseGPXFileController@destroy');

    Route::post('courses/{course}/images', 'CourseImagesController@store');
    Route::delete('courses/{course}/images/{media}', 'CourseImagesController@destroy');
    Route::post('courses/{course}/images-order', 'CourseImagesOrderController@update');

    Route::post('published-events', 'PublishedEventsController@store');

    Route::post('events/{event}/youtube-videos', 'EventYoutubeVideosController@store');

    Route::post('embeddable-videos/{video}', 'EmbeddableVideosController@update');
    Route::delete('embeddable-videos/{video}', 'EmbeddableVideosController@delete');

    Route::post('galleries', 'GalleriesController@store');
    Route::post('galleries/{gallery}', 'GalleriesController@update');
    Route::delete('galleries/{gallery}', 'GalleriesController@delete');
    Route::post('galleries/{gallery}/images', 'GalleryImagesController@store');
    Route::delete('galleries/{gallery}/images/{image}', 'GalleryImagesController@delete');
    Route::post('galleries/{gallery}/image-order', 'GalleryImagesOrderController@update');

    Route::post('coaches', 'CoachesController@store');
    Route::post('coaches/{coach}', 'CoachesController@update');
    Route::delete('coaches/{coach}', 'CoachesController@delete');

    Route::post('published-coaches', 'PublishedCoachesController@store');
    Route::delete('published-coaches/{coach}', 'PublishedCoachesController@destroy');

    Route::post('coaches/{coach}/youtube-videos', 'CoachYoutubeVideosController@store');

    Route::post('coaches/{coach}/profile-pic', 'CoachProfilePicController@store');
    Route::delete('coaches/{coach}/profile-pic', 'CoachProfilePicController@destroy');

    Route::post('ambassadors', 'AmbassadorsController@store');
    Route::post('ambassadors/{ambassador}', 'AmbassadorsController@update');
    Route::delete('ambassadors/{ambassador}', 'AmbassadorsController@delete');

    Route::post('published-ambassadors', 'PublishedAmbassadorsController@store');
    Route::delete('published-ambassadors/{ambassador}', 'PublishedAmbassadorsController@destroy');

    Route::post('ambassadors/{ambassador}/youtube-videos', 'AmbassadorYoutubeVideosController@store');

    Route::post('ambassadors/{ambassador}/profile-pic', 'AmbassadorProfilePicController@store');
    Route::delete('ambassadors/{ambassador}/profile-pic', 'AmbassadorProfilePicController@destroy');

    Route::post('promotions', 'PromotionsController@store');
    Route::post('promotions/{promotion}', 'PromotionsController@update');
    Route::delete('promotions/{promotion}', 'PromotionsController@delete');

    Route::post('public-promotions', 'PublicPromotionsController@store');
    Route::delete('public-promotions/{promotion}', 'PublicPromotionsController@destroy');

    Route::post('featured-promotions', 'FeaturedPromotionsController@store');

    Route::post('promotions/{promotion}/image', 'PromotionImageController@store');
    Route::delete('promotions/{promotion}/image', 'PromotionImageController@destroy');

});

Route::group([
    'prefix'     => 'admin/pages',
    'middleware' => ['auth'],
    'namespace'  => 'Admin\Pages'
], function () {
    Route::view("dashboard", "admin.spa-base");

    Route::get('previews/{translation}', 'TranslationPreviewController@show');
});

