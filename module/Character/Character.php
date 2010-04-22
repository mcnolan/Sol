<?php

class Character extends AModule {


public function main() {
	$this->theme()->content = $this->theme()->loadTemplate('character','Character');
	$this->theme()->render();
}
public function install() {}

}
