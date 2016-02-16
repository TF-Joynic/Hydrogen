@ECHO ** %date% %time% Hydrogen Framework Cli Running! **

@IF "%1" == "" ECHO Usage: xxx, Not allowed
@REM @SET %%v=""
@REM @FOR /L  %%i IN (1, 1, 9) DO @set %%v = !%%v%%i!
@REM @ECHO %%v
@php.exe cli.php %1 %2 %3 %4 %5 %6 %7 %8 %9