
$q1 = 'SELECT S_WRITERS, COUNT( S_ID ) ccn FROM SONGS GROUP BY S_WRITERS ORDER BY ccn DESC LIMIT 10 ';
$q2 = 'SELECT S_MUSICIAN, COUNT( S_ID ) ccn FROM SONGS GROUP BY S_MUSICIAN ORDER BY ccn DESC LIMIT 20';
$q3 = 'SELECT S_SINGERS, COUNT( S_ID ) ccn FROM SONGS GROUP BY S_SINGERSa ORDER BY ccn DESC LIMIT 20';
$q4 = 'SELECT M_DIRECTOR, COUNT( M_ID ) ccn FROM MOVIES GROUP BY M_DIRECTOR ORDER BY ccn DESC LIMIT 20 ';
$q5 = 'SELECT M_PRODUCER, COUNT( M_ID ) ccn FROM MDETAILS GROUP BY M_PRODUCER ORDER BY ccn DESC LIMIT 20 ';
$q6 = 'SELECT M_BANNER, COUNT( M_ID ) ccn FROM MDETAILS GROUP BY M_BANNER ORDER BY ccn DESC LIMIT 20 ';
$q7 = 'SELECT M_DISTRIBUTION, COUNT( M_ID ) ccn FROM MDETAILS GROUP BY M_DISTRIBUTION ORDER BY ccn DESC LIMIT 20 ';
$q8 = 'SELECT M_EDITOR, COUNT( M_ID ) ccn FROM MDETAILS GROUP BY M_EDITOR ORDER BY ccn DESC LIMIT 20 ';
$q9 = 'SELECT M_CAMERA, COUNT( M_ID ) ccn FROM MDETAILS GROUP BY M_CAMERA ORDER BY ccn DESC LIMIT 20 ';
$q10 = 'SELECT M_ART, COUNT( M_ID ) ccn FROM MDETAILS GROUP BY M_ART ORDER BY ccn DESC LIMIT 20 ';
$q11 = 'SELECT M_DESIGN, COUNT( M_ID ) ccn FROM MDETAILS GROUP BY M_DESIGN ORDER BY ccn DESC LIMIT 20 ';
$q12 = 'SELECT M_STORY, COUNT( M_ID ) ccn FROM MDETAILS GROUP BY M_STORY ORDER BY ccn DESC LIMIT 20 ';
$q13 = 'SELECT M_SCREENPLAY, COUNT( M_ID ) ccn FROM MDETAILS GROUP BY M_SCREENPLAY ORDER BY ccn DESC LIMIT 20 ';
$q14 = 'SELECT M_DIALOG, COUNT( M_ID ) ccn FROM MDETAILS GROUP BY M_DIALOG ORDER BY ccn DESC LIMIT 20 ';