<?php
/**
 * Scheduling Dashboard module
 *
 * @package RosarioSIS
 * @subpackage modules
 */

/**
 * Dashboard Default Scheduling module
 *
 * @since 4.0
 *
 * @param  boolean $export   Exporting data, defaults to false. Optional.
 * @return string  Dashboard module HTML.
 */
function DashboardDefaultScheduling()
{
	require_once 'ProgramFunctions/DashboardModule.fnc.php';

	$profile = User( 'PROFILE' );

	$data = '';

	if ( $profile === 'admin' )
	{
		$data = DashboardSchedulingAdmin();
	}

	return DashboardModule( 'Scheduling', $data );
}

if ( ! function_exists( 'DashboardSchedulingAdmin' ) )
{
	/**
	 * Dashboard data
	 * Scheduling module & admin profile
	 *
	 * @since 4.0
	 *
	 * @return array Dashboard data
	 */
	function DashboardSchedulingAdmin()
	{
		$courses_RET = DBGet( DBQuery( "SELECT COUNT(COURSE_ID) AS COURSES_NB,
			COUNT(DISTINCT SUBJECT_ID) AS SUBJECTS_NB
		FROM COURSES
		WHERE SYEAR='" . UserSyear() . "'
		AND SCHOOL_ID='" . UserSchool() . "'" ) );

		$cp_RET = DBGet( DBQuery( "SELECT COUNT(COURSE_PERIOD_ID) AS COURSE_PERIODS_NB
		FROM COURSE_PERIODS
		WHERE SYEAR='" . UserSyear() . "'
		AND SCHOOL_ID='" . UserSchool() . "'
		AND MARKING_PERIOD_ID IN(" . GetAllMP( 'QTR', UserMP() ) . ")" ) );

		$cp_mp_title = _( 'Course Periods' ) . ' (' . GetMP( UserMP(), 'SHORT_NAME' ) . ')';

		$data = array(
			_( 'Courses' ) => $courses_RET[1]['COURSES_NB'],
			ngettext( 'Subject', 'Subjects', $courses_RET[1]['SUBJECTS_NB'] ) => $courses_RET[1]['SUBJECTS_NB'],
			$cp_mp_title => $cp_RET[1]['COURSE_PERIODS_NB'],
		);

		return $data;
	}
}