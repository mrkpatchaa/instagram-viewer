<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \Curl\Curl;
use MetzWeb\Instagram\Instagram;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('profile/index', [
            'user' =>  $request->user()
        ]);
        // return view('profile/index');
    }

    /**
     * Show the photos.
     *
     * @return \Illuminate\Http\Response
     */
    public function photos(Request $request)
    {
        return view('profile/photos', [
            'user' =>  $request->user(),
            'pageType' => 'photos'
        ]);
    }

    /**
     * Show the likes.
     *
     * @return \Illuminate\Http\Response
     */
    public function likes(Request $request)
    {
        return view('profile/photos', [
            'user' =>  $request->user(),
            'pageType' => 'likes'
        ]);
    }

    /**
     * Perform instagram actions.
     *
     * @return \Illuminate\Http\Response
     */
    public function instagram(Request $request)
    {
        if($request->isMethod('delete')) {
            // Delete instagram link
            $user = $request->user();
            $user->insta_token = '';
            $user->insta_user = '';
            $user->insta_name = '';
            $user->insta_picture = '';
            $user->insta_active = 0;

            $user->save();

            return redirect('profile')->with('warning', 'Your instagram account has been disconnected successfully!');
        }

        if($request->isMethod('get')) {
            // Check if we have a code
            $code = $request->input('code');
            if($code) {
                // var_dump($code);
                // var_dump(config('site.client_id'));

                // Now we get the access_token
                $curl = new Curl();
                $curl->post('https://api.instagram.com/oauth/access_token', array(
                    'client_id' => config('site.client_id'),
                    'client_secret' => config('site.client_secret'),
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => config('site.redirect_uri'),
                    'code' => $code,
                ));
                if ($curl->error) {
                    // echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage;
                    return redirect('profile')->with('error', 'An error occured, Please try again! [' . $curl->errorMessage . ']');
                }

                // Get the user and update
                // $user = Auth::user();
                $user = $request->user();
                $user->insta_token = $curl->response->access_token;
                $user->insta_user = $curl->response->user->username;
                $user->insta_name = $curl->response->user->full_name;
                $user->insta_picture = $curl->response->user->profile_picture;
                $user->insta_active = 1;

                $user->save();

                return redirect('profile')->with('message', 'Your instagram account connected successfully!');

            }

            // Check if we have an error
            $error = $request->input('error');
            $error_reason = $request->input('error_reason');
            $error_description = $request->input('error_description');

            if($error) {
                // var_dump($error);
                // var_dump($error_reason);
                // var_dump($error_description);
                return redirect('profile')->with('error', 'An error occured, Please try again! [' . $error_description . ']');
            }
        }
    }

    /**
     * Cal instagram api.
     *
     * @return \Illuminate\Http\Response
     */
    public function instagramApi(Request $request)
    {
        // if($request->isAjax()) {
        // echo "ajax";
        $idx = $request->input('idx');
        $type = $request->input('type');

        // var_dump($idx);
        // var_dump($type);
        // }

        $user = $request->user();

        // Instagram object
        $instagram = new Instagram(array(
            'apiKey' => config('site.client_id'),
            'apiSecret' => config('site.client_secret'),
            'apiCallback' => config('site.redirect_uri')
        ));
        $instagram->setAccessToken($user->insta_token);
        $photos = array(
            'status' => 0,
            'data' => array()
        );
        if($idx <= 2) {
        if($type == 'photos')
            $instaPhotos = $instagram->getUserMedia();
        if($type == 'likes')
            $instaPhotos = $instagram->getUserLikes();
        foreach ($instaPhotos->data as $instaPhoto) {
            $photos['data'][] = array (
                'idx' => $instaPhoto->id,
                'title' => $instaPhoto->caption->text,
                'src' => $instaPhoto->images->standard_resolution->url,
                'thumbnail' => $instaPhoto->images->thumbnail->url,
                'w' => $instaPhoto->images->standard_resolution->width,
                'h' => $instaPhoto->images->standard_resolution->height,
            );
        };
        $photos['status'] = 1;
        }
        // echo '<pre>';
        // print_r($likes);
        // echo '<pre>';
        return response()->json($photos);
    }
}
