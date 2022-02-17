/**
 * Test Files JS.
 *
 * @since 1.0.0
 * @package Surveyfunnel_Lite/JSTests
 */

// @codingStandardsIgnoreStart
//Phpcs doesn't support ReactJS and Phpcbf messes the code,so we cant use it.
/**
 * @jest-environment jsdom
 */
import React, { Component } from "react";
import PropTypes from "prop-types";
import Tab from "../../../HelperComponents/Tab";
import Tabs from "../../../HelperComponents/Tabs";

import renderer from 'react-test-renderer';

test('HelperComponents Tabs test', () => {

    const onClick= function(){
        return;
    };
    const label= "test tab";
    const activeTab= "none";
    var props = {
        label:'none'
    }
    var child ={
            props
    }
    const children = [
        child
    ]

    const component1 = renderer.create( <Tabs onClick={onClick} label={label} activeTab={activeTab} children={children} /> );
	let tree = component1.toJSON();
	expect(tree).toMatchSnapshot();

});
// @codingStandardsIgnoreEnd