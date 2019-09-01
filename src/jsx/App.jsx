import 'core-js/stable';
import 'regenerator-runtime/runtime';
import objectFitImages from 'object-fit-images';
import React from 'react';
import { render } from 'react-dom';

import Navigator from './Navigator';

if (module.hot) {
	module.hot.accept();
}

objectFitImages(false, { watchMQ: false, skipTest: false });

const targetDOM = document.getElementById('app-root');
if (targetDOM) render(<Navigator />, targetDOM);
