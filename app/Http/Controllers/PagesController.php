<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Topic;
use App\Models\Answer;
use App\Models\Course;
use App\Models\Rating;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\Enrolled;
use App\Models\Question;
use App\Models\Curriculum;
use App\Models\Transaction;
use App\Models\QuizEnrolled;
use App\Models\CurriculumLecture;
use App\Models\Earning;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{

    public function shareUser($link){
        $exist = User::where("link", $link)->exists();
        if($exist){
            $user = User::where("link", $link)->first();
            $courses = Course::where(["instructor" => $user->id, "status" => "active"])->latest()->get();
            return view("shared-user", compact("user", "courses"));
        }else{
            return abort(404, "Page not Found");
        }
    }

    public function shareCourse($link){
        $exist = Course::where("link", $link)->exists();
        if($exist){
            $course = Course::where(["link" => $link])->first();
            $instructor = User::find($course->instructor);

            $rate = Rating::where('courseid', $course->id)->avg('vote');
            $ratings = Rating::where('courseid', $course->id)->get();
            $curriculum = Curriculum::where('courseid', $course->id)->get();

            $enrolled = Enrolled::where([ 'courseid' => $course->id])->count();
            if(Auth::check()){
                $isLike = Like::where(['userid' => auth()->user()->id, 'courseid' =>  $course->id])->count();
            }else{
                $isLike = 0;
            }
            if($isLike == 0){
                $like = 'like';
            }else{
                $like = 'unlike';
            }
            $students = Enrolled::where([ 'courseid' => $course->id])->latest()->get();
            return view("shared-course", compact('course', 'instructor', 'rate', 'ratings', 'curriculum', 'enrolled', 'like', 'students'));
        }else{
            return abort(404, "Page not Found");
        }
    }
    public function index(){

        $totalSales = 0;
        $totalEnroll = 0;
        $totalCourse = 0;
        $totalStudents = 0;
        $wallet = null;
        $quizEarning = 0;
        $courseEarning = 0;
        $courses = null;
        $quiz = null;
        $instructors = null;
        $featured = null;
        $allCourses = null;
        $category = null;
        $categoryCoureses = null;
        $topics = null;
        if(Auth::check()){
            if(auth()->user()->type == 'instructor' || auth()->user()->type == 'admin'){
                $instructorCourses = Course::where('instructor', auth()->user()->id)->get();
                foreach ($instructorCourses as $row) {
                    $totalSales += Transaction::where(['courseid' => $row->id, 'status' => 'success'])->count();
                    $totalEnroll += Enrolled::where(['courseid' => $row->id])->count();
                }
                $totalCourse  = Course::where('instructor',auth()->user()->id)->count();
                $totalStudents = User::where('type', 'student')->count();
                $courses = Course::where(['instructor' => auth()->user()->id, 'status' => 'active'])->latest()->limit(5)->get();
                $quiz = Question::where(['userid' => auth()->user()->id])->latest()->limit(5)->get();
                $courseEarning = Earning::where(["type" => "course", "userid" => auth()->user()->id])->sum("amount");
                $quizEarning = Earning::where(["type" => "quiz", "userid" => auth()->user()->id])->sum("amount");

            }else{
                $courses = Course::where('status', 'active')->latest()->limit(5)->get();
                $featured = Course::where('status', 'active')->orderBy('created_at', 'asc')->limit(5)->get();
                $instructors = User::where('type', 'instructor')->latest()->limit(5)->get();
            }
            $wallet = Wallet::where("userid", auth()->user()->id)->first();
        }else{
            if(isset($_GET['cat'])){
                $allCourses = Course::where(['status' =>  'active', 'category' => $_GET['cat']])->latest()->limit(10)->get();
                $category = Category::find($_GET['cat']);
                $categoryCoureses = Course::where(['status' =>  'active', 'category' => $_GET['cat']])->count();
            }else{
                $allCourses = Course::where(['status' =>  'active'])->latest()->limit(10)->get();
            }

            $topics = Topic::latest()->get();
        }
        return view("index", compact('quiz', 'topics', 'totalSales', 'totalEnroll', 'totalCourse', 'totalStudents', 'instructors', 'courses', 'featured', 'allCourses', 'category', 'categoryCoureses', 'wallet', 'quizEarning', 'courseEarning'));
    }

    public function about(){
        return view("about");
    }

    public function contact(){
        return view("contact");
    }

    public function students(){
        return view("students");
    }

    public function lectures(){
        return view("lectures");
    }

    public function privacy_policy(){
        return view('privacy_policy');
    }
    public function cookie(){
        return view('cookie');
    }

    public function instructorAgreement(){
        return view('instructor-agreement');
    }

    public function terms(){
        return view('terms');
    }

    public function faq(){
        return view('faq');
    }

    public function courseSection(){
        return view('course');
    }

    public function exploreView(){
        return view('course-explore');
    }

    public function addCategory(){
        $categories = Category::latest()->get();
        $children_categories = Category::whereNull('parentid')->latest()->get();
        return view('dashboard.category.add', compact("children_categories", "categories"));
    }

    public function editCategory($id){
        $category = Category::find($id);
        $children_categories = Category::whereNull('parentid')->latest()->get();
        return view('dashboard.category.edit', compact('category', 'children_categories'));
    }

    public function viewCategory($id){
        $category = Category::find($id);
        $courses = Course::where(['category' => $id, 'status' => 'active'])->latest()->get();
        return view('dashboard.category.view', compact('category', 'courses'));
    }
    public function explore(){
        $courses = Course::where('status', 'active')->latest()->get();
        return  view('dashboard.explore.index', compact('courses'));
    }

    public function saved(){
        return view('dashboard.saved.index');
    }

    public function analysis(){

        if(auth()->user()->type == "instructor"){

            $courses = Course::where(['status' => 'active', 'instructor' => auth()->user()->id])->latest()->get();
            $quiz = Question::where(["userid" => auth()->user()->id])->latest()->get();
        }else{
            $courses = Course::where('status' , 'active', )->latest()->get();
            $quiz = Question::latest()->get();
        }

        return view('dashboard.analysis.index', compact('courses', "quiz"));
    }

    public function cart(){
        return  view('dashboard.cart');
    }


    public function courses(){
        if(auth()->user()->type == 'instructor' || auth()->user()->type == 'admin'){
            if(auth()->user()->type == "instructor"){
                $courses = Course::where('instructor', auth()->user()->id)->latest()->get();
            }else{
                $courses = Course::latest()->get();
            }
        }else{
            $courses = Course::where('status', 'active')->latest()->get();
        }
        return view('dashboard.courses.index', compact('courses'));
    }

    public function createCourse(){
        $categories = Category::whereNull('parentid')->latest()->get();
        return view('dashboard.courses.create', compact('categories'));
    }

    public function viewCourse($id){
        $course = Course::find($id);
        $instructor = User::find($course->instructor);
        $rate = Rating::where('courseid', $course->id)->avg('vote');
        $ratings = Rating::where('courseid', $course->id)->get();
        $curriculum = Curriculum::where('courseid', $course->id)->get();

        $enrolled = Enrolled::where([ 'courseid' => $id])->count();
        if(Auth::check()){
            $isLike = Like::where(['userid' => auth()->user()->id, 'courseid' =>  $id])->count();

        }else{
            $isLike = 0;
        }
        if($isLike == 0){
            $like = 'like';
        }else{
            $like = 'unlike';
        }
        $students = Enrolled::where([ 'courseid' => $id])->latest()->get();
        return view('dashboard.courses.view', compact('course', 'instructor', 'rate', 'ratings', 'curriculum', 'enrolled', 'like', 'students'));
    }

    public function editCourse($id){
        $course = Course::find($id);
        $categories = Category::whereNull('parentid')->latest()->get();
        $subcategory = Category::find($course->subcategory);
        return view('dashboard.courses.edit', compact('categories', 'course', "subcategory"));
    }

    public function editCurriculum($id){
        $curriculum = Curriculum::find($id);
        return view('dashboard.courses.curriculum.edit', compact('curriculum'));
    }

    public function addLecture($id){
        $curriculum = Curriculum::where('id', $id)->first();
        $lectures = CurriculumLecture::where('curriculumid', $id)->latest()->get();
        $curriculumid = $id;
        $courseid = $curriculum->courseid;
        return view('dashboard.courses.curriculum.lectures.add', compact('lectures', 'curriculumid', 'courseid'));
    }

    public function editLecture($id){
        $lecture = CurriculumLecture::find($id);
        return view('dashboard.courses.curriculum.lectures.edit', compact('lecture'));
    }

    public function notifications(){
        return view('dashboard.notifications.index');
    }

    public function reviews(){
        return view('dashboard.reviews.index');
    }

    public function earnings(){
        $wallet = Wallet::where("userid", auth()->user()->id)->first();
        $earnings = Earning::where("userid", auth()->user()->id)->latest()->get();
        $total_earns = Earning::where("userid", auth()->user()->id)->whereNotIn("type", ["withdrawal"])->sum("amount");
        return view('dashboard.earnings.index', compact("wallet", "earnings", "total_earns"));
    }


    public function payouts(){
        return view('dashboard.payouts.index');
    }

    public function statements(){
        return view('dashboard.statements.index');
    }

    public function verifications(){
        return view('dashboard.verifications.index');
    }

    public function users(){
        $users = User::latest()->get();
        return view('dashboard.users.index', compact('users'));
    }

    public function viewUser($id){
        $user = User::find($id);
        if(auth()->user()->type == 'student'){
            $courses = Enrolled::where('userid', $id)->latest()->get();
        }else{
            $courses = Course::where('instructor', $id)->latest()->get();
        }
        $earnings = Earning::where("userid", $id)->latest()->get();
        $wallet = Wallet::where("userid", $id)->first();
        $total_earns = Earning::where("userid", $id)->whereNotIn("type", ["withdrawal"])->sum("amount");
        return view('dashboard.users.view', compact('user', 'courses', 'earnings', 'wallet','total_earns'));
    }
    public function settings(){
        return view('dashboard.settings.index');
    }

    public function instructorApplication(){
        return view('dashboard.instructor-application');
    }

    public function stream($courseid, $curriculumid, $lectureid){
        $course = Course::find($courseid);
        $instructor = User::find($course->instructor);
        $lecture = CurriculumLecture::find($lectureid);
        return view('dashboard.courses.stream', compact('course','instructor', 'lecture'));
    }

    public function purchased($status){
        return view('dashboard.purchased', compact('status'));
    }

    // Quiz ---
    public function quiz(){
        $topics = Topic::latest()->get();
        return view("dashboard.quiz.index", compact("topics"));
    }

    public function quizResult(){
        $answers = DB::table('answers')->select('ref')->distinct()->get();
        return view("dashboard.quiz.result", compact("answers"));

    }

    public function takeQuiz(){
        $questions = null;
        $topics = null;
        if(isset($_GET['query'])){
            $questions = Question::where('topicid', $_GET['id'])->latest()->get();
        }
        $topics = Topic::latest()->get();
        return view("dashboard.quiz.take", compact("questions", "topics"));
    }

    public function startTest($id){
        $question = Question::where("id", $id)->first();
        return view("dashboard.quiz.test", compact("question", "id"));
    }
    public function viewTest($id){
        $question = Question::where("id", $id)->first();
        if(Auth::check()){
            $iquizenrolled = QuizEnrolled::where(['userid' => auth()->user()->id, "questionid" => $id])->exists();
            $isLike = Like::where(['userid' => auth()->user()->id, 'questionid' =>  $id])->count();
        }else{
            $iquizenrolled = false;
            $isLike = 0;
        }
        $students = QuizEnrolled::where(["questionid" => $id])->latest()->get();
        $totalenrolled = count($students);
        $instructor = User::find($question->userid);

        if($isLike == 0){
            $like = 'like';
        }else{
            $like = 'unlike';
        }
        $topic = Topic::find($question->topicid);
        $quiz = Quiz::where(['qid' => $id ])->latest()->get();

        return view("dashboard.quiz.view", compact("topic", "quiz", "students", "like", "question", "id", "iquizenrolled", 'totalenrolled', 'instructor'));
    }

    public function quizPage($id){
        $question = Question::find($id);
        $quiz = Quiz::where("qid", $id)->get();
        $topic = Topic::find($question->topicid);
        return view("dashboard.quiz.quiz", compact("question", "quiz", "topic"));
    }

    public function editQuiz($id){
        $quiz = Quiz::where("id", $id)->first();
        return view("dashboard.quiz.edit", compact("quiz"));
    }

    public function addTopic(){
        return view("dashboard.quiz.topic.add");
    }

    public function editTopic($id){
        $topic = Topic::find($id);
        return view("dashboard.quiz.topic.edit", compact("topic"));
    }

    public function questions($id){
        $questions = Question::where(["userid" => auth()->user()->id, "topicid" => $id])->latest()->get();
        $topic = Topic::find($id);
        return view("dashboard.quiz.question.index", compact("questions", "topic"));
    }

    public function addQuestions($id){
        $topic = Topic::find($id);
        return view("dashboard.quiz.question.add", compact("topic"));
    }

    public function editQuestion($id){
        $question = Question::find($id);
        $topic = Topic::find($question->topicid);
        return view("dashboard.quiz.question.edit", compact("topic", "question"));
    }

    public function resultScore($ref){
        $answers = Answer::where("ref", $ref)->get();
        $wrong = Answer::where(["ref" => $ref, "mark" => "0"])->get();
        $right = Answer::where(["ref" => $ref, "mark" => "1"])->get();
        $quiz = Quiz::where(["topic" => $answers->first()->topic, "qid" => $answers->first()->qid])->get();

        return view("dashboard.quiz.result-score", compact("answers", "wrong", "right", "quiz"));
    }

    public function importQuestions(){
        $topics = Topic::latest()->get();
        return view("dashboard.quiz.import-questions", compact("topics"));
    }

    public function messages(){
        return view("dashboard.messages.index");
    }
}

