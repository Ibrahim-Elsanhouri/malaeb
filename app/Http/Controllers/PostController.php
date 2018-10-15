<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostFormRequest;
use App\Posts;
use App\User;
use DB;
use Auth;
use Illuminate\Http\Request;
use Redirect;

class PostController extends Controller {
	//
	public function index() {
		//fetch 5 posts from database which are active and latest
		$posts = Posts::where('active', 1)->orderBy('created_at', 'desc')->paginate(5);
		$autor = function ($author_id) {
			if (!$author_id) {
				return false;
			}
            return User::find($author_id)->name;

		};
		//page heading
		$title = '';
		//return home.blade.php template from resources/views folder
		return view('blog')->withPosts($posts)->withTitle($title);
	}
	public function create(Request $request) {
		// if user can post i.e. user is admin or author
		if ($request->user()->can_post()) {
			return view('posts.create');
		} else {
			return redirect('/')->withErrors('لا تملك صلاحيات الكتابة.');
		}
	}
	public function store(Request $request) {
		$post            = new Posts();
		$post->title     = $request->get('title');
		if ( Posts::where('title', $post->title )->first() ){
			$message = 'عفوا اسم المقال موجود مسبقا اختر اسم اخر';
			 return back()->withInput()->withErrors([$message, $message]);
			// return redirect('/')
		}
		$post->body      = trim($request->get('body'));
		$post->slug      = str_slug($post->title);
		$post->author_id = $request->user()->id;
		if ($request->has('save')) {
			$post->active = 0;
			$message      = 'Post saved successfully';
		} else {
			$post->active = 1;
			$message      = 'تم النشر بنجاح';
		}
		$post->save();
		return redirect('edit/' . $post->slug)->withMessage($message);
	}


	public function show($slug) {
		$post = Posts::where('slug', $slug)->first();
		if (!$post) {
			return redirect('/')->withErrors('requested page not found');
		}
		$comments = $post->comments;
        $author = function ($author_id) {
            if (!$author_id) {
                return false;
            }
            return User::find($author_id)->name;

        };
		return view('posts.show')->withPost($post)->withComments($comments)->withAuthor($author);
	}
	public function edit(Request $request, $slug) {
		$post = Posts::where('slug', $slug)->first();
		if ($post && ($request->user()->id == $post->author_id || $request->user()->is_admin())) {
			return view('posts.edit')->with('post', $post);
		}

		return redirect('/')->withErrors('you have not sufficient permissions');
	}
	public function update(Request $request) {
		$post_id = $request->input('post_id');
		$post    = Posts::find($post_id);
		if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
			$title     = $request->input('title');
			$slug      = str_slug($title);
			$duplicate = Posts::where('slug', $slug)->first();
			if ($duplicate) {
				if ($duplicate->id != $post_id) {
					return redirect('edit/' . $post->slug)->withErrors('العنوان المدخل موجود بالفعل')->withInput();
				} else {
					$post->slug = $slug;
				}
			}
			$post->title = $title;
			$post->body  = $request->input('body');
			if ($request->has('save')) {
				$post->active = 0;
				$message      = 'تم حفظ المقال بنجاح.';
				$landing      = 'edit/' . $post->slug;
			} else {
				$post->active = 1;
				$message      = 'تم حفظ المقال بنجاح.';
				$landing      = 'blog/'.$post->slug;
			}
			$post->save();
			return redirect($landing)->withMessage($message);
		} else {
			return redirect('/')->withErrors('you have not sufficient permissions');
		}
	}
	public function destroy(Request $request) {
		$post = Posts::find($request->id);
		if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
			$post->delete();
			$data['message'] = 'تم حذف المقال بنجاح.';
		} else {
			$data['errors'] = 'خطأ';
		}
		return redirect('/user/'.Auth::id().'/posts')->with($data);
	}

	public function user_posts($id) {
		$posts = Posts::where('author_id', $id)->where('active', 1)->orderBy('created_at', 'desc')->paginate(5);
		$title = User::find($id)->name;
		return view('blog')->withPosts($posts)->withTitle($title);
	}
	/*
		     * Display all of the posts of a particular user
		     *
		     * @param Request $request
		     * @return view
	*/
	public function user_posts_all(Request $request) {
		//
		$user  = $request->user();
		$posts = Posts::where('author_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
		$title = $user->name;
		return view('blog')->withPosts($posts)->withTitle($title);
	}
	/*
		     * Display draft posts of a currently active user
		     *
		     * @param Request $request
		     * @return view
	*/
	public function user_posts_draft(Request $request) {
		//
		$user  = $request->user();
		$posts = Posts::where('author_id', $user->id)->where('active', 0)->orderBy('created_at', 'desc')->paginate(5);
		$title = $user->name;
		return view('blog')->withPosts($posts)->withTitle($title);
	}
	/**
	 * profile for user
	 */
	public function profile(Request $request, $id) {
		if ( Auth::guest() )
			return redirect('/');
		$data['user'] = User::find($id);
		if (!$data['user']) {
			return redirect('/');
		}

		if ($request->user() && $data['user']->id == $request->user()->id) {
			$data['author'] = true;
		} else {
			$data['author'] = null;
		}
		$data['get_type'] = function($slug){
			switch ($slug) {
				case 'pg_owner':
					return 'مالك ملعب';
					break;
				case 'marketer':
					return 'مسوق';
					break;
				case 'admin':
					return 'مدير';
					break;
				default:
					return 'لاعب';
					break;
			}
		};
		$city = DB::table('cities')->where( 'id',$request->user()->city )->first();
		$data['user']->city = $city->name_ar;
		$data['cities']     = $cities_array;
		$data['comments_count']     = $data['user']->comments->count();
		$data['posts_count']        = $data['user']->posts->count();
		$data['posts_active_count'] = $data['user']->posts->where('active', '1')->count();
		$data['posts_draft_count']  = $data['posts_count'] - $data['posts_active_count'];
		$data['latest_posts']       = $data['user']->posts->where('active', '1')->take(5);
		$data['latest_comments']    = $data['user']->comments->take(5);
		return view('admin.profile', $data);
	}


}
