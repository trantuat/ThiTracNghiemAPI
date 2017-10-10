<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \App\Repositories\Question\QuestionRepositoryInterface::class,
            \App\Repositories\Question\QuestionRepository::class
        );
        $this->app->singleton(
            \App\Repositories\Answer\AnswerRepositoryInterface::class,
            \App\Repositories\Answer\AnswerRepository::class
        );
        $this->app->singleton(
            \App\Repositories\User\UserRepositoryInterface::class,
            \App\Repositories\User\UserRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Quiz\QuizzRepositoryInterface::class,
            \App\Repositories\Quiz\QuizzRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Quiz\QuizzQuestionRepositoryInterface::class,
            \App\Repositories\Quiz\QuizzQuestionRepository::class
        );

        $this->app->singleton(
            \App\Repositories\User\InfoRepositoryInterface::class,
            \App\Repositories\User\InfoRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Answer\AnswerStudentRepositoryInterface::class,
            \App\Repositories\Answer\AnswerStudentRepository::class
        );

        $this->app->singleton(
            \App\Repositories\History\HistoryRepositoryInterface::class,
            \App\Repositories\History\HistoryRepository::class
        );
    }
}
