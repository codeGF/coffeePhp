<?php


class Index extends Controller
{

	public function main()
	{
	    $this->auto_->model->tm->s();
	    $this->display_();
	}
}
