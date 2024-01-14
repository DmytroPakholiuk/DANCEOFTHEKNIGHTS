#!/bin/bash

success=0
error=1

OUT_COLOR_RED='\033[0;31m'
OUT_COLOR_GREEN='\033[0;32m'
OUT_COLOR_BLUE='\033[0;34m'
OUT_NO_COLOR='\033[0m'

# вывод цветного сообщения
function output() {
  case $2 in
    success)
      echo -e "$OUT_COLOR_GREEN$1$OUT_NO_COLOR"
      ;;
    error)
      echo -e "$OUT_COLOR_RED$1$OUT_NO_COLOR"
      ;;
    * | info)
      echo -e "$OUT_COLOR_BLUE$1$OUT_NO_COLOR"
    ;;
  esac
}

function yesno() {
  default=''
  if [[ ! (-z $2)  ]]; then
   default=" [$2]"
  fi
  question="$1 (y/n)${default}:"
  while true; do
    read -p "${question}" answer
    if [[ ${answer} = "" ]]; then
        answer=$2
    fi
    case ${answer} in
      Y | y | yes ) return ${success};;
      N | n | no ) return ${error};;
      * ) echo "Please answer yes or no.";;
    esac
  done
}


# запуск docker-compose
function makeDocker() {

  if ! (docker info); then
    output "docker is not running. Can not continue" error
    return ${error}
  fi
  if ! (hash docker-compose 2>/dev/null); then
    output "docker-compose is not running. Can not continue." error
    return ${error}
  fi
  if ! [[ -f 'docker-compose.yml' ]]; then
    output "docker-compose.yml not found. Can not continue." error
    return ${error}
  fi

  if ! (docker network inspect dance_of_the_knights_network >/dev/null); then
        docker network create --gateway 175.10.20.1 --subnet 175.10.20.0/24 dance_of_the_knights_network
  fi

  if ! docker-compose up -d; then
    output "docker-compose could not" error
    return ${error}
  fi

  output "Docker containers have been set up successfully." success
  return ${success}
}


function start() {
    docker-compose up -d
    docker exec dance_of_the_knights_php /bin/bash -c "php dance watch_prices"
}

function buildDanceOfTheKnightsPhp() {
    output "Setting up php with composer" info

    docker exec dance_of_the_knights_php /bin/bash -c "composer install --ignore-platform-reqs"
    docker exec dance_of_the_knights_php /bin/bash -c "composer update"
    docker exec dance_of_the_knights_php /bin/bash -c "php vendor/bin/codecept bootstrap"

    docker exec dance_of_the_knights_php /bin/bash -c "php dance watch_prices"

    output "Setup successful" success
}

function runUnits() {
    output "Running units" info

    docker exec dance_of_the_knights_php /bin/bash -c "XDEBUG_MODE=coverage ./vendor/bin/phpunit tests --coverage-text"
}

function checkHosts() {
    HOSTS='127.0.0.1 danceoftheknights.com'
    if grep "${HOSTS}" /etc/hosts | grep -v '^#'; then
      echo "${HOSTS} уже присутствуют в /etc/hosts"
    else
      sudo /bin/bash -c "echo -e '\n${HOSTS}' >> /etc/hosts";
      output "${HOSTS} have been added successfully to /etc/hosts." success
    fi
    output "The site is available at \n " info
    output "danceoftheknights.com:30080 " info
}

function fullInstall() {
    if ! makeDocker; then
        return
    fi
    buildDanceOfTheKnightsPhp
    checkHosts
}

function showInstallMenu() {
  INSTALL='Full project installation'
  START='Start the containers'
  DANCE_OF_THE_KNIGHTS_BUILD='Build dance of the knights'
  RUN_UNITS='Run unit tests'

  options=(
      "${INSTALL}"
      "${START}"
      "${DANCE_OF_THE_KNIGHTS_BUILD}"
      "${RUN_UNITS}"
  )

    select opt in "${options[@]}"; do
      case ${opt} in
      ${INSTALL})
        output "Full project installation" success
        fullInstall
        return
        ;;
      ${START})
        start
        return
        ;;
      ${DANCE_OF_THE_KNIGHTS_BUILD})
        buildDanceOfTheKnightsPhp
        return
        ;;
      ${RUN_UNITS})
        runUnits
        return
        ;;
      *)
    output 'Choose one of the shown options:' error
    showInstallMenu
    return
      ;;
    esac
  done
}

showInstallMenu

