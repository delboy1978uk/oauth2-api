<?php

namespace BoneMvc;

use Symfony\Component\Console\Application as SymfonyConsoleApplication;

class ConsoleApplication extends SymfonyConsoleApplication
{
    public function getLongVersion()
    {
        return "
                            7 ,::::::::                         
                          + :::::::::,  :                 ?     
                          :::::::::: ::::::~              7777  
      ,: 7               ::::::::: :: =7777~             7+777  
        +  77777           :::::::::: 777777777           ,77+77=7  
        ~77 :77777          ::::::=7,7777777777          77+777777  
        77777777:77777      :::,777 ?7777777777~     +7777777777I?  
    :777 7II7777777777  ::77     7~+     77:  ?7777777          
      =?       : I7777 = :       77:     ~7 777777 ,            
                     7 7 7      7  7     77,777 =               
                     ,7 777    7I  777I77?7: ,                  
                     , , 7777777    77777:                      
                       7:   7777  ??777? ~                      
                       :  ,7 7777777777 7I7 =                   
                     , ::::77=77777?77777+7777I ?               
                   777:: ::777:      +777 + ~77777              
               777777  :,:::777:     777        777777      I7  
     , 777777+I777    :::,::77777,~+7777I        ,,777I 777777, 
      7 7777I77,~     :::::,: 777777777~           ~=7777 77:7  
         77777 77 +      ,:::: ::7 777777I=               7777  77  
     ,77?777           : : :      = ,                 +777?77~  
     +777 7            :,:::~                            77I    
        77?              +::                                    
                         =::                                    
                          :           ____   __   __ _  ____  _  _  _  _   ___
                          :          (  _ \\ /  \\ (  ( \\(  __)( \\/ )/ )( \\ / __)
                                      ) _ ((  O )/    / ) _) / \\/ \\ \\/ /( (__
                                     (____/ \\__/ \\_)__)(____)\\_)(_/ \\__/  \\___)
";
    }
}