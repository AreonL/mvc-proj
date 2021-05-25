array function roll
    CALL selected to check for update to score
    CALL bonus to score

    CALL trueRoll to trueRollArray

    IF true is in trueRollArray THEN
        roll dices that are true
    
    GET all dices and add to data array

    GET sum of all dices and add to session 

    ADD one too rollCounter
    SET total summa to data array

    return data

function selected
    GET selection
    GET sum of all dices with selected number

    IF selection is equal to one THEN
        SET number to session (key + number) ADD value of sum to key
    IF selection is greater than one THEN
        CALL function special selection
    ALSO
    IF selection exsists THEN
        SET rollCounter back to 0
        SET session able to roll again
        ADD number of all dices too total summa

function special selection
    SET sum equals 0

    SWITCH name of selection
        CASE name equals selection
            CALL function with name
        CASE name equals selection
            CALL function with name
        ..
        CASE name equals selection
                    CALL function with name
    IF not exists THEN
        return sum

function pair
    SET biggest variable equals 0

    FOREACH dice number in dicehand
        IF dice hand has 2 of the same THEN
            SET biggest to dice number
    return biggest + biggest
        