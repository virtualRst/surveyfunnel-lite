import React, { Component, useContext } from "react";
import { useRef } from 'react';
import { useDrag, useDrop } from 'react-dnd';

export default function (props) {

    const ref = useRef(null);
    const { id, text, index, item } = props;

    const [{ handlerId }, drop] = useDrop({
        accept: 'card' + item.itemType,
        collect(monitor) {
            return {
                handlerId: monitor.getHandlerId(),
            };
        },
        hover(data, monitor) {
            if (!ref.current) {
                return;
            }
            const dragIndex = data.index;
            const hoverIndex = index;
            // Don't replace items with themselves
            if (dragIndex === hoverIndex) {
                return;
            }
            // Determine rectangle on screen
            const hoverBoundingRect = ref.current?.getBoundingClientRect();
            // Get vertical middle
            const hoverMiddleY = (hoverBoundingRect.bottom - hoverBoundingRect.top) / 2;
            // Determine mouse position
            const clientOffset = monitor.getClientOffset();
            // Get pixels to the top
            const hoverClientY = clientOffset.y - hoverBoundingRect.top;
            // Dragging downwards
            if (dragIndex < hoverIndex && hoverClientY < hoverMiddleY) {
                return;
            }
            // Dragging upwards
            if (dragIndex > hoverIndex && hoverClientY > hoverMiddleY) {
                return;
            }
            // Time to actually perform the action
            //moveCard(dragIndex, hoverIndex, item);

            data.index = hoverIndex;
        },
    });

    const [{ isDragging }, drag] = useDrag({
        type: 'card' + item.type,
        item: () => {
            return { id, index };
        },
        collect: (monitor) => ({
            isDragging: monitor.isDragging(),
        }),
    });

    const style = {
        opacity: isDragging ? 0 : 1,
        cursor: isDragging ? 'pointer' : 'move',
    }
    drag(drop(ref));


    const editCard = function() {
        console.log('hello world');
    }

    const deleteCard = function() {
        console.log('hello delete world');
    }
    
    return (
        <div ref={ref} className="cardBox" style={style}>
            <div>{item.name}</div>
            <div className="card-flex">
                <button onClick={editCard}>edit</button>
                <button onClick={deleteCard}>delete</button>
            </div>
        </div>
    );
}