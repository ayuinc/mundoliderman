#!/bin/bash

function imprime() {
  NOW="`date +"%d/%m/%Y %H:%M:%S"`"
  echo "$NOW - $1" >> jobs.log
}

SECRET="76bfaf88ae4d178d004bad31146faeed";
HOST="http://localhost/mundoliderman"


imprime "Inicia Job Inscribir Lidermans"
OUTPUT=$(curl -s -X POST $HOST/jobs/inscribir-lidermans-a-capacitaciones/$SECRET)
imprime "$OUTPUT"
imprime "Fin Job Inscribir Lidermans"
