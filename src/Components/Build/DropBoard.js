//Phpcs doesn't support ReactJS and Phpcbf messes the code,so we cant use it.
// @codingStandardsIgnoreStart
/**
 * DropBoard JS.
 *
 * @since 1.0.0
 * @package Surveyfunnel_Lite/Components/Build
 */
import { useDrop } from "react-dnd";
import React, { useContext } from "react";
import { BuildContext } from "../Context/BuildContext";
import ShowBoard from "./ShowBoard";
import Card from "./Card";

const backgroundColor = (elementType)=>{
    if(elementType === 'StartScreen'){
        return {backgroundColor:"#FFE7F5"};
    }
    else if(elementType === 'ContentElements' ){
        return {backgroundColor:"#E2EDFF"};
    }
    else{
        return {backgroundColor:"#F4F4D5"};
    }
}

const DropBoard = ({ ele }) => {
    const { List } = useContext(BuildContext);
	// useDrop returns drop ref and some monitoring values.
    const [{ canDrop, isOver }, drop] = useDrop(() => ({
		// it accepts unique item type which was specified in useDrag in BuildElement Component.
        accept: ele.itemType,
        drop: () => ({ name: "DropBoard" }),
        collect: (monitor) => ({
            isOver: monitor.isOver(),
            canDrop: monitor.canDrop(),
        }),
    }));
    const isActive = canDrop && isOver;
    
    return (
        <div
            ref={drop}
            role={"DropBoard"}
            style={backgroundColor(ele.type)}
            className="surveyfunnel-lite-dropboard"
        >
            {isActive ? "Release to drop" : "Drag a box here"}
            <h2>{ele.name}</h2>
            <ShowBoard itemType={ele.itemType}></ShowBoard>
        </div>
    );
};

export default DropBoard;
// @codingStandardsIgnoreStart
