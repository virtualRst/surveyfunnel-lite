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
import React, { useCallback, useRef, useState } from "react";
import { RgbaColorPicker } from "react-colorful";
import useClickOutside from "../../../HelperComponents/useClickOutside";
import * as ColorPicker from "../../../HelperComponents/ColorPicker";
import renderer from 'react-test-renderer';

test('Popover picker test', async () => {

    const color={
        r:155,
        g:155,
        b:155,
        a:0.8
    }
    const onchange = ()=>{
        return;
    }

    const component = renderer.create( <ColorPicker.PopoverPicker color={color} onchange={onchange} /> );
	let tree = component.toJSON();
	expect(tree).toMatchSnapshot();
});
// @codingStandardsIgnoreEnd