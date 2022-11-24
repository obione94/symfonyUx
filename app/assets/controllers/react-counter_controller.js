import { Controller } from 'stimulus';
import ReactDOM from 'react-dom';
import React from 'react';
import Counter from "../components/Counter";

export default class extends Controller {

    connect() {
        ReactDOM.render(
            <Counter />,
            this.element
        )
    }
}
