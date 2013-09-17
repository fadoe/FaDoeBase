<?php

namespace FaDoeBase\View\Helper\Navigation;

use \RecursiveIteratorIterator;
use Zend\Navigation\AbstractContainer;
use Zend\Navigation\Page\AbstractPage;
use Zend\View\Helper\Navigation\Menu as NavigationMenu;

class TwitterBootstrapMenu extends NavigationMenu
{

    public function htmlify(AbstractPage $page, $escapeLabel = true, $depth = 0)
    {
        // get label and title for translating
        $label = $page->getLabel();
        $title = $page->getTitle();

        // translate label and title?
        if (null !== ($translator = $this->getTranslator())) {
            $textDomain = $this->getTranslatorTextDomain();
            if (is_string($label) && !empty($label)) {
                $label = $translator->translate($label, $textDomain);
            }
            if (is_string($title) && !empty($title)) {
                $title = $translator->translate($title, $textDomain);
            }
        }

        // get attribs for element
        $attribs = array(
            'id'     => $page->getId(),
            'title'  => $title,
            'class'  => $page->getClass()
        );

        // does page have a href?
        $href = $page->getHref();
        if ($href) {
            $element = 'a';
            $attribs['href'] = $href;
            $attribs['target'] = $page->getTarget();
        } else {
            $element = 'span';
        }

        if ($page->hasChildren()) {
            $attribs['class'] .= ' dropdown-toggle';
            $attribs['data-toggle'] = 'dropdown';
        }

        $html = '<' . $element . $this->htmlAttribs($attribs) . '>';

        $icon = $page->icon;
        if (null !== $icon) {
            $html .= '<span class="' . $icon . '"></span> ';
        }

        if ($escapeLabel === true) {
            $escaper = $this->view->plugin('escapeHtml');
            $html .= $escaper($label);
        } else {
            $html .= $label;
        }

        if ($page->hasChildren() && $depth === 0) {
            $html .= ' <span class="caret"></span>';
        }

        $html .= '</' . $element . '>';

        return $html;
    }

    /**
     * Renders a normal menu (called from {@link renderMenu()})
     *
     * @param AbstractContainer $container container to render
     * @param string $ulClass CSS class for first UL
     * @param string $indent initial indentation
     * @param int|null $minDepth minimum depth
     * @param int|null $maxDepth maximum depth
     * @param bool $onlyActive render only active branch?
     * @return string
     */
    protected function renderNormalMenu(AbstractContainer $container,
                                   $ulClass,
                                   $indent,
                                   $minDepth,
                                   $maxDepth,
                                   $onlyActive,
                                   $escapeLabels
    ) {
        $html = '';

        // find deepest active
        $found = $this->findActive($container, $minDepth, $maxDepth);
        if ($found) {
            $foundPage = $found['page'];
            $foundDepth = $found['depth'];
        } else {
            $foundPage = null;
        }

        // create iterator
        $iterator = new RecursiveIteratorIterator($container,
                            RecursiveIteratorIterator::SELF_FIRST);
        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }

        // iterate container
        $prevDepth = -1;
        foreach ($iterator as $page) {
            $depth = $iterator->getDepth();
            $isActive = $page->isActive(true);
            if ($depth < $minDepth || !$this->accept($page)) {
                // page is below minDepth or not accepted by acl/visibility
                continue;
            } elseif ($onlyActive && !$isActive) {
                // page is not active itself, but might be in the active branch
                $accept = false;
                if ($foundPage) {
                    if ($foundPage->hasPage($page)) {
                        // accept if page is a direct child of the active page
                        $accept = true;
                    } elseif ($foundPage->getParent()->hasPage($page)) {
                        // page is a sibling of the active page...
                        if (!$foundPage->hasPages() ||
                            is_int($maxDepth) && $foundDepth + 1 > $maxDepth) {
                            // accept if active page has no children, or the
                            // children are too deep to be rendered
                            $accept = true;
                        }
                    }
                }

                if (!$accept) {
                    continue;
                }
            }

            // make sure indentation is correct
            $depth -= $minDepth;
            $myIndent = $indent . str_repeat(' ', $depth);

            if ($depth > $prevDepth) {
                // start new ul tag
                if ($ulClass && $depth == 0) {
                    $ulClass = ' class="' . $ulClass . '"';
                } else {
                    $ulClass = ' class="dropdown-menu"';
                }
                $html .= $myIndent . '<ul' . $ulClass . '>' . self::EOL;
            } elseif ($prevDepth > $depth) {
                // close li/ul tags until we're at current depth
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $ind = $indent . str_repeat(' ', $i);
                    $html .= $ind . ' </li>' . self::EOL;
                    $html .= $ind . '</ul>' . self::EOL;
                }
                // close previous li tag
                $html .= $myIndent . ' </li>' . self::EOL;
            } else {
                // close previous li tag
                $html .= $myIndent . ' </li>' . self::EOL;
            }

            // render li tag and page
            $liClass = '';
            if ($page->hasChildren()) {
                if ($depth > 0) {
                    $liClass .= ' dropdown-submenu';
                } else {
                    $liClass .= ' dropdown';
                }
            }

            $liClass .= $isActive ? ' active' : '';
            $liClass = (strlen($liClass)) ? ' class="' . $liClass . '" ' : '';

            $html .= $myIndent . ' <li' . $liClass . '>' . self::EOL
                   . $myIndent . ' ' . $this->htmlify($page, $escapeLabels, $depth) . self::EOL;

            // store as previous depth for next iteration
            $prevDepth = $depth;
        }

        if ($html) {
            // done iterating container; close open ul/li tags
            for ($i = $prevDepth+1; $i > 0; $i--) {
                $myIndent = $indent . str_repeat(' ', $i-1);
                $html .= $myIndent . ' </li>' . self::EOL
                       . $myIndent . '</ul>' . self::EOL;
            }
            $html = rtrim($html, self::EOL);
        }

        return $html;
    }

}
