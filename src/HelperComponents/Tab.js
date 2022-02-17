/**
 * Tab JS.
 *
 * @since 1.0.0
 * @package Surveyfunnel_Lite/HelperComponents
 */

import React, { Component } from "react";
import PropTypes from "prop-types";
// @codingStandardsIgnoreStart
//Phpcs doesn't support ReactJS and Phpcbf messes the code,so we cant use it.

class Tab extends Component {
	static propTypes = {
		activeTab: PropTypes.string.isRequired,
		label: PropTypes.string.isRequired,
		onClick: PropTypes.func.isRequired,
	};

	onClick                      = () => {
		const { label, onClick } = this.props;
		onClick( label );
	};

	render() {
		const {
			onClick,
			props: { activeTab, label },
		} = this;

		let className = "tab-list-item";

		if (activeTab === label) {
			className += " tab-list-active";
		}

		return (
			<li className = {className} onClick = {onClick} >
				{label}
			</li>
		);
	}
}

export default Tab;
// @codingStandardsIgnoreEnd
