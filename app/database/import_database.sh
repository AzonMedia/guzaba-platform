#!/usr/bin/env bash

ENO_SUCCESS=0; ETXT[0]="ENO_SUCCESS"
ENO_GENERAL=1; ETXT[1]="ENO_GENERAL"
green_color='\033[0;32m' # Green
red_color='\033[0;31m' # Red
no_color='\033[0m' # No Color

if [ $# -ne 2 ]; then
    printf "${red_color}[ERROR] Could not import database - required arguments are missing.${no_color}\n"
    exit $ENO_GENERAL
fi

database_file=$1
env_file=$2

. "$env_file"

mysql_host="localhost" #!!!!!FIXME!!!!!!
port="$GUZABAPLATFORM_PLATFORM_APPLICATION_MYSQLCONNECTION_PORT"
pass="$GUZABAPLATFORM_PLATFORM_APPLICATION_MYSQLCONNECTION_PASSWORD"
database="$GUZABAPLATFORM_PLATFORM_APPLICATION_MYSQLCONNECTION_DATABASE"

mysql_command=mysql

mysql_cnf="$(mktemp -t tmp.XXXXXXXXXX)"
printf "[client]\n" > "$mysql_cnf"
printf "host=%s\n" "$host" >> "$mysql_cnf"
printf "user=%s\n" "root" >> "$mysql_cnf"
printf "password=%s\n" "$pass" >> "$mysql_cnf"

function spinner() {
    local delay=0.75
    local spinstr='|/-\'
    while :
    do
        local temp=${spinstr#?}
        printf " [%c]  " "$spinstr"
        local spinstr=$temp${spinstr%"$temp"}
        sleep $delay
        printf "\b\b\b\b\b\b"
    done
    printf "    \b\b\b\b"
}

function cleanup() {
    rm -f "$mysql_cnf"
    kill -9 "$SPIN_PID"
}

printf "Starting database import..."
spinner &
SPIN_PID=$!
trap cleanup `seq 0 15`

if [ ! -z "$port" ] && [ ! -z "$pass" ] && [ ! -z "$database" ]; then
    if [ ! -x "$(command -v "$mysql_command")" ]; then
        printf "${red_color}[ERROR] Could not import database - \"%s\" command is not installed.${no_color}\n" "$mysql_command"
        exit $ENO_GENERAL
    fi

    result="$($mysql_command --defaults-file="$mysql_cnf" --defaults-group-suffix=client --protocol tcp -e ";" 2>&1)"
    if [ $? -ne 0 ]; then
        printf "${red_color}[ERROR] Could not connect to database with provided credentials.${no_color}\n%s\n" "$database" "$result"
        exit $ENO_GENERAL
    fi

    result="$($mysql_command --defaults-file="$mysql_cnf" --defaults-group-suffix=client --protocol tcp --skip-column-names -e "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA='${database}';")"
    if [ $result -ne 0 ]; then
        printf "${red_color}[ERROR] Database '%s' exists and is not empty. Skipping import...${no_color}\n%s\n" "$database"
        exit $ENO_GENERAL
    fi

    result="$($mysql_command --defaults-file="$mysql_cnf" --defaults-group-suffix=client --protocol tcp -e "CREATE DATABASE IF NOT EXISTS ${database} /*\!40100 DEFAULT CHARACTER SET utf8mb4 */;" 2>&1)"
    if [ $? -ne 0 ]; then
        printf "${red_color}[ERROR] Could not create database.${no_color}\n%s\n" "$database" "$result"
        exit $ENO_GENERAL
    fi

    result="$("$mysql_command" --defaults-file="$mysql_cnf" --defaults-group-suffix=client --protocol tcp "${database}" < "${database_file}" 2>&1)"
    if [ $? -ne 0 ]; then
        printf "${red_color}[ERROR] Could not import database file \"%s\".${no_color}\n%s\n" "$database_file" "$result"
        exit $ENO_GENERAL
    fi
else
    printf "${red_color}[ERROR] Could not import database some of required data is missing in env file \"%s\"${no_color}\n" "$env_file"
    exit $ENO_GENERAL
fi

printf "${green_color}[OK] Database imported succefully.${no_color}\n"

exit $ENO_SUCCESS
